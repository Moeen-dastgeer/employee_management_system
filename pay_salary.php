<?php
include "config.php";
include "auth_check.php";

// Only Admin or HR can mark salary as paid
if (!(isAdmin() || isHR())) {
    echo "Access denied!";
    exit;
}

// Ensure salary ID is passed
if (!isset($_GET['id'])) {
    echo "Invalid request!";
    exit;
}

$salary_id = intval($_GET['id']);
$paid_date = date('Y-m-d');

// Prepare statement to update status
$stmt = $conn->prepare("UPDATE salaries SET status = 'Paid', paid_date = ? WHERE id = ?");
$stmt->bind_param("si", $paid_date, $salary_id);

if ($stmt->execute()) {
    header("Location: salary_list.php?msg=Salary marked as paid");
    exit;
} else {
    echo "Failed to update salary status!";
}
?>
