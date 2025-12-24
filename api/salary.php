<?php
// api/salary.php

header("Content-Type: application/json");

// Read JSON input
$input = json_decode(file_get_contents("php://input"), true);

// Required field
if (!isset($input['basic_salary'])) {
    echo json_encode([
        "status" => false,
        "message" => "basic_salary is required"
    ]);
    exit;
}

$basicSalary = $input['basic_salary'];

// Optional fields (default to 0)
$hra         = isset($input['hra']) ? $input['hra'] : 0;
$allowances  = isset($input['allowances']) ? $input['allowances'] : 0;
$deductions  = isset($input['deductions']) ? $input['deductions'] : 0;

// Validate numeric values
if (
    !is_numeric($basicSalary) ||
    !is_numeric($hra) ||
    !is_numeric($allowances) ||
    !is_numeric($deductions)
) {
    echo json_encode([
        "status" => false,
        "message" => "All salary fields must be numeric"
    ]);
    exit;
}

// Validate non-negative values
if ($basicSalary < 0 || $hra < 0 || $allowances < 0 || $deductions < 0) {
    echo json_encode([
        "status" => false,
        "message" => "Salary values cannot be negative"
    ]);
    exit;
}

// Calculate gross and net salary
$grossSalary = $basicSalary + $hra + $allowances;
$netSalary   = $grossSalary - $deductions;

// Success response
echo json_encode([
    "status"  => true,
    "message" => "Salary calculated successfully",
    "data"    => [
        "basic_salary" => $basicSalary,
        "hra"          => $hra,
        "allowances"   => $allowances,
        "deductions"   => $deductions,
        "gross_salary" => $grossSalary,
        "net_salary"   => $netSalary
    ]
]);
