<?php
// api/bmi.php

header("Content-Type: application/json");

// Read JSON input
$input = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (!isset($input['height_cm']) || !isset($input['weight_kg'])) {
    echo json_encode([
        "status" => false,
        "message" => "height_cm and weight_kg are required"
    ]);
    exit;
}

$heightCm = $input['height_cm'];
$weightKg = $input['weight_kg'];

// Validate numeric values
if (!is_numeric($heightCm) || !is_numeric($weightKg)) {
    echo json_encode([
        "status" => false,
        "message" => "height_cm and weight_kg must be numeric"
    ]);
    exit;
}

// Validate positive values
if ($heightCm <= 0 || $weightKg <= 0) {
    echo json_encode([
        "status" => false,
        "message" => "height_cm and weight_kg must be greater than 0"
    ]);
    exit;
}

// Convert height from cm to meters
$heightM = $heightCm / 100;

// Calculate BMI
$bmi = $weightKg / ($heightM * $heightM);
$bmi = round($bmi, 2);

// Determine BMI category
if ($bmi < 18.5) {
    $category = "Underweight";
} elseif ($bmi < 25) {
    $category = "Normal";
} elseif ($bmi < 30) {
    $category = "Overweight";
} else {
    $category = "Obese";
}

// Success response
echo json_encode([
    "status"  => true,
    "message" => "BMI calculated successfully",
    "data"    => [
        "bmi"      => $bmi,
        "category" => $category
    ]
]);
