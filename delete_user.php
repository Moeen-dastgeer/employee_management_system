<?php
include "config.php";
include "auth_check.php";

// Only admin can delete users
if (!isAdmin()) {
    echo "Access denied!";
    exit;
}

// Get user ID
if (!isset($_GET['id'])) {
    echo "Invalid request!";
    exit;
}

$id = intval($_GET['id']);

// Prevent deleting your own account (optional but recommended)
if ($_SESSION['user']['id'] == $id) {
    echo "You cannot delete your own account!";
    exit;
}

// Delete user securely
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: users.php?msg=User deleted successfully");
    exit;
} else {
    echo "Failed to delete user!";
}
?>
