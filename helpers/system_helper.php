<?php

/**
 * Display returned message
 */
function showMessage(): void
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
 *
 * @return bool
 */
function redirect(bool $page = FALSE): bool
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
