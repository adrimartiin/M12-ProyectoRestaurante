<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: ./public/dashboard.php");
    exit();
} else {
    header("Location: ./public/login.php");
    exit();
}

