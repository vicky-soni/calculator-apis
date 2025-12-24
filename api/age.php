<?php
// api/age.php

header("Content-Type: application/json");

// Read JSON input
$input = json_decode(file_get_contents("php://input"), true);

// Check required parameter
if (!isset($input['dob'])) {
    echo json_encode([
        "status" => false,
        "message" => "dob is required"
    ]);
    exit;
}

$dob = trim($input['dob']);

// Validate date format
if (!strtotime($dob)) {
    echo json_encode([
        "status" => false,
        "message" => "Invalid date format. Use YYYY-MM-DD"
    ]);
    exit;
}

// Create DateTime objects
$birthDate = new DateTime($dob);
$today     = new DateTime();

// Check future date
if ($birthDate > $today) {
    echo json_encode([
        "status" => false,
        "message" => "Date of birth cannot be a future date"
    ]);
    exit;
}

// Calculate age in completed years
$age = $today->diff($birthDate)->y;

// Success response
echo json_encode([
    "status"  => true,
    "message" => "Age calculated successfully",
    "data"    => [
        "age_years" => $age
    ]
]);
