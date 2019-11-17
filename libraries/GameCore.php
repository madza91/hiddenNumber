<?php

/**
 * Class GameCore
 */
class GameCore
{
    const STATUS_IN_PROGRESS = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CLOSED = 2;

    /** @var Database */
    protected $db;

    /**
     * GameCore constructor.
     */
    function __construct()
    {
        $this->db = new Database();
    }

    /**
     * @param string $username
     *
     * @return bool
     */
    public function newGame(string $username): bool
    {
        $secretNumber = rand(-10000, 10000);

        $this->db->query('INSERT INTO games (username, secret_number, guess_total)
                            VALUES (:username, :secret_number, 0)');
        $this->db->bind(':username', $username);
        $this->db->bind(':secret_number', $secretNumber);
        $this->db->execute();

        $_SESSION['username']    = $username;
        $_SESSION['game_id']     = $this->db->lastInsertId();
        $_SESSION['game_status'] = self::STATUS_IN_PROGRESS;

        return true;
    }

    /**
     * @param int $game_id
     */
    public function gameClose(int $game_id): void
    {
        $this->setStatus($game_id, self::STATUS_CLOSED);
        $_SESSION['game_status'] = self::STATUS_CLOSED;

        $gameInfo = $this->getInfo($game_id);

        $_SESSION['message'] = "Your secret number was <b>{$gameInfo->secret_number}</b>. Please, next time be more patient!";
        redirect('');
    }

    /**
     * @param int $chosen_number
     * @param int $game_id
     */
    public function gameTry(int $chosen_number, int $game_id): void
    {
        $this->increaseTry($game_id);

        $gameInfo = $this->getInfo($game_id);

        $secretNumber = $gameInfo->secret_number;
        $guess_total  = $gameInfo->guess_total;

        if ($chosen_number == $secretNumber) {
            $this->setStatus($_SESSION['game_id'], self::STATUS_COMPLETED);

            $_SESSION['message']     = "Well done! You have successfully guessed secret number in {$guess_total} times!";
            $_SESSION['game_status'] = self::STATUS_COMPLETED;
        } elseif ($chosen_number > $secretNumber) {
            $_SESSION['message'] = "Your number is bigger than ours. Try number: {$guess_total}";
        } elseif ($chosen_number < $secretNumber) {
            $_SESSION['message'] = "Your number is smaller than ours. Try number: {$guess_total}";
        }
    }

    /**
     * @param int $game_id
     *
     * @return stdClass|null
     */
    public function getInfo(int $game_id): ?stdClass
    {
        $this->db->query('SELECT * FROM games WHERE id = :game_id');
        $this->db->bind(':game_id', $game_id);

        return $this->db->single();
    }

    /**
     * @param int $game_id
     * @param int $status
     *
     * @return bool
     */
    public function setStatus(int $game_id, int $status): bool
    {
        $this->db->query('UPDATE games SET status = :status WHERE id = :game_id LIMIT 1');
        $this->db->bind(':status', $status);
        $this->db->bind(':game_id', $game_id);

        return $this->db->execute();
    }

    /**
     * @param int $game_id
     *
     * @return bool
     */
    public function increaseTry(int $game_id): bool
    {
        $this->db->query('UPDATE games SET guess_total = guess_total + 1 WHERE id = :game_id LIMIT 1');
        $this->db->bind(':game_id', $game_id);

        return $this->db->execute();
    }

    /**
     * @return array
     */
    public function getScore(): array
    {
        $this->db->query('SELECT * FROM games WHERE status = 1 ORDER BY guess_total ASC, created_at DESC');
        $results = $this->db->resultset();

        return $results;
    }

    /**
     * @return int
     */
    public function totalPlayed(): int
    {
        $this->db->query('SELECT COUNT(*) AS total FROM games');
        $result = $this->db->single();

        return $result->total;
    }
}
