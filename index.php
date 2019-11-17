<?php

// Autoload
function my_autoloader($class) {
    include "libraries/{$class}.php";
}

spl_autoload_register('my_autoloader');
require "core/init.php";

$gameCore = new GameCore();

if (isset($_POST['new_game'])) {
    $username = $_POST['username'];
    $gameCore->newGame($username);
}

if (isset($_POST['do_guess'])) {
    $chosen_number = $_POST['new_guess'];
    $gameCore->gameTry($chosen_number, $_SESSION['game_id']);
}

if (isset($_POST['close_game'])) {
    $gameCore->gameClose($_SESSION['game_id']);
}

$gameID        = (isset($_SESSION['game_id'])) ? $_SESSION['game_id'] : false;
$isGameStarted = ($gameID && $_SESSION['game_status'] == 0) ? true : false;

if ($isGameStarted) {
    $template = new Template('in_game');
} else {
    $template              = new Template('new_game');
    $template->score       = $gameCore->getScore();
    $template->username    = (isset($_SESSION['username'])) ? $_SESSION['username']: '';
    $template->totalPlayed = $gameCore->totalPlayed();
}

$template->title = $config['title'];

echo $template;
