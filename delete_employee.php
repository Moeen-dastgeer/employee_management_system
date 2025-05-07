<?php
include "config.php";
include "auth_check.php";

// Only Admin or HR can delete employees
if (!(isAdmin() || isHR())) {
    echo "Access denied!";
    exit;
}

// Check for employee ID in URL
if (!isset($_GET['id'])) {
    echo "Invalid request!";
    exit;
}

$id = intval($_GET['id']);

// Delete employee securely
$stmt = $conn->prepare("DELETE FROM employees WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: employees.php?msg=Employee deleted successfully");
    exit;
} else {
    echo "Failed to delete employee!";
}
?>
