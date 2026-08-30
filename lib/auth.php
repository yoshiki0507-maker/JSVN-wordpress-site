<?php
declare(strict_types=1);

session_start();

if (empty($_SESSION['authed'])) {
    header('Location: login.php');
    exit;
}
