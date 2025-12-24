<?php
// api/percentage.php

header("Content-Type: application/json");

// Read JSON input
$input = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (!isset($input['obtained_marks']) || !isset($input['total_marks'])) {
    echo json_encode([
        "status" => false,
        "message" => "obtained_marks and total_marks are required"
    ]);
    exit;
}

$obtained = $input['obtained_marks'];
$total    = $input['total_marks'];

// Validate numbers
if (!is_numeric($obtained) || !is_numeric($total)) {
    echo json_encode([
        "status" => false,
        "message" => "Marks must be numeric"
    ]);
    exit;
}

// Validate total marks
if ($total <= 0) {
    echo json_encode([
        "status" => false,
        "message" => "total_marks must be greater than 0"
    ]);
    exit;
}

// Validate obtained marks
if ($obtained < 0 || $obtained > $total) {
    echo json_encode([
        "status" => false,
        "message" => "obtained_marks must be between 0 and total_marks"
    ]);
    exit;
}

// Calculate percentage
$percentage = ($obtained / $total) * 100;
$percentage = round($percentage, 2);

// Decide status (simple rule)
$statusResult = ($percentage >= 33) ? "Pass" : "Fail";

// Success response
echo json_encode([
    "status"  => true,
    "message" => "Percentage calculated successfully",
    "data"    => [
        "percentage" => $percentage,
        "status"     => $statusResult
    ]
]);
