<?php
/**
 * Created by PhpStorm.
 * User: Nemanja
 * Date: 5.3.2017
 * Time: 13:50
 */



class GameCore
{


    /**
     * GameCore constructor.
     */
    function __construct()
    {
        $this->db = new Database();
    }

    public function newGame($username)
    {

        $secretNumber = rand(-10000, 10000);

        $this->db->query('INSERT INTO games (username, secret_number, guess_total)
                            VALUES (:username, :secret_number, 0)');
        $this->db->bind(':username', $username);
        $this->db->bind(':secret_number', $secretNumber);

        $this->db->execute();

        $lastId = $this->db->lastInsertId();



        $_SESSION['username']   = $username;
        $_SESSION['game_id']    = $lastId;

        // Status: 0 - In progress, 1 - Completed, 2 - Closed
        $_SESSION['game_status'] = 0;

        return true;
    }


    /**
     * @param $game_id
     */
    public function gameClose($game_id)
    {

        $gameStatusClosed = 2;

        $this->setStatus($game_id, $gameStatusClosed);

        $_SESSION['game_status'] = $gameStatusClosed;

        $gameInfo = $this->getInfo($game_id);

        $_SESSION['message'] = "Your secret number was <b>{$gameInfo->secret_number}</b>. Please, next time be more patient!";
        redirect('');
    }


    /**
     * @param $chosen_number
     * @param $game_id
     */
    public function gameTry($chosen_number, $game_id)
    {


        $this->increaseTry($game_id);

        $gameInfo = $this->getInfo($game_id);

        $secretNumber = $gameInfo->secret_number;
        $guess_total = $gameInfo->guess_total;


        if ($chosen_number == $secretNumber) {

            $this->setStatus($_SESSION['game_id']);

            $_SESSION['message'] = "Well done! You have successfully guessed secret number in {$guess_total} times!";
            $_SESSION['game_status'] = 1;
        } elseif ($chosen_number > $secretNumber) {
            $_SESSION['message'] = "Your number is bigger than ours. Try number: {$guess_total}";
        } elseif ($chosen_number < $secretNumber) {
            $_SESSION['message'] = "Your number is smaller than ours. Try number: {$guess_total}";
        }

    }


    /**
     * @param $game_id
     * @return mixed
     */
    public function getInfo($game_id)
    {

        $this->db->query('SELECT * FROM games WHERE id = :game_id');
        $this->db->bind(':game_id', $game_id);

        return $this->db->single();
    }


    /**
     * @param $game_id
     * @param int $status
     * @return bool
     */
    public function setStatus($game_id, $status = 1)
    {

        $this->db->query('UPDATE games SET status = :status WHERE id = :game_id LIMIT 1');
        $this->db->bind(':status', $status);
        $this->db->bind(':game_id', $game_id);
        $this->db->execute();

        return true;
    }


    /**
     * @param $game_id
     * @return bool
     */
    public function increaseTry($game_id)
    {

        $this->db->query('UPDATE games SET guess_total = guess_total + 1 WHERE id = :game_id LIMIT 1');
        $this->db->bind(':game_id', $game_id);
        $this->db->execute();

        return true;
    }


    /**
     * @return mixed
     */
    public function getScore()
    {

        $this->db->query('SELECT * FROM games WHERE status = 1 ORDER BY guess_total ASC, created_at DESC');
        $results = $this->db->resultset();

        return $results;
    }


    public function totalPlayed()
    {

        $this->db->query('SELECT COUNT(*) AS total FROM games');
        $result = $this->db->single();

        return $result->total;

    }

}