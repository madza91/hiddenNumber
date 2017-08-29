<?php
/**
 * Created by PhpStorm.
 * User: Nemanja
 * Date: 5.3.2017
 * Time: 13:49
 */

/**
 * Display returned message
 */
function showMessage()
{

    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }

}

/**
 * Redirect To Page, push balloon message for display
 *
 * @param bool $page
 */
function redirect($page = FALSE)
{

    if (!empty($page) && is_string($page)) {
        $location = $page;
    } else {
        $location = ACTUAL_LINK;
    }


    // Redirect
    header('Location: ' . $location);

    exit;
}