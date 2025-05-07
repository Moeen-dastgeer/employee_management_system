<?php
include "config.php";
include "auth_check.php";

// Only admin or HR can update leave status
if (!(isAdmin() || isHR())) {
    echo "Access denied!";
    exit;
}

// Check if both parameters are set
if (!isset($_GET['id']) || !isset($_GET['action'])) {
    echo "Invalid request!";
    exit;
}

$leave_id = intval($_GET['id']);
$action = $_GET['action'];

if ($action == 'approve') {
    $status = 'Approved';
} elseif ($action == 'reject') {
    $status = 'Rejected';
} else {
    echo "Invalid action!";
    exit;
}

// Update leave status securely
$stmt = $conn->prepare("UPDATE leaves SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $leave_id);

if ($stmt->execute()) {
    header("Location: leave_requests.php?msg=Leave $status successfully");
} else {
    echo "Error updating leave!";
}
?>
