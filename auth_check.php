<?php
session_start();

// Redirect to login page if user not logged in
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// ROLE BASED FUNCTIONS
function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

function isHR() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'hr';
}

function isEmployee() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'employee';
}
?>
