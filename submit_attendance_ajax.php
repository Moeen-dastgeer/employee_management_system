<?php
include "config.php";
include "auth_check.php";

if (!(isAdmin() || isHR())) {
    http_response_code(403);
    echo "Unauthorized";
    exit;
}

$emp_id = $_POST['employee_id'] ?? null;
$status = $_POST['status'] ?? null;
$date = $_POST['date'] ?? date('Y-m-d');

// Check if already marked
$check = $conn->prepare("SELECT * FROM attendance WHERE employee_id = ? AND date = ?");
$check->bind_param("is", $emp_id, $date);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    // ✅ Update if already exists
    $stmt = $conn->prepare("UPDATE attendance SET status = ? WHERE employee_id = ? AND date = ?");
    $stmt->bind_param("sis", $status, $emp_id, $date);
    $stmt->execute();
    echo "Attendance updated";
} else {
    // ✅ Insert new if not exists
    $stmt = $conn->prepare("INSERT INTO attendance (employee_id, date, status) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $emp_id, $date, $status);
    $stmt->execute();
    echo "Attendance saved";
}
