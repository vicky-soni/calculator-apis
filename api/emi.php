<?php
// api/emi.php

header("Content-Type: application/json");

// Read JSON input
$input = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (
    !isset($input['loan_amount']) ||
    !isset($input['interest_rate']) ||
    !isset($input['tenure_years'])
) {
    echo json_encode([
        "status" => false,
        "message" => "loan_amount, interest_rate and tenure_years are required"
    ]);
    exit;
}

$loanAmount   = $input['loan_amount'];
$interestRate = $input['interest_rate'];
$tenureYears  = $input['tenure_years'];

// Validate numeric values
if (!is_numeric($loanAmount) || !is_numeric($interestRate) || !is_numeric($tenureYears)) {
    echo json_encode([
        "status" => false,
        "message" => "All inputs must be numeric"
    ]);
    exit;
}

// Validate positive values
if ($loanAmount <= 0 || $interestRate <= 0 || $tenureYears <= 0) {
    echo json_encode([
        "status" => false,
        "message" => "Values must be greater than 0"
    ]);
    exit;
}

// EMI calculation
$monthlyRate = ($interestRate / 12) / 100;   // Monthly interest rate
$months      = $tenureYears * 12;             // Total months

$emi = ($loanAmount * $monthlyRate * pow(1 + $monthlyRate, $months)) /
       (pow(1 + $monthlyRate, $months) - 1);

$emi = round($emi, 2);

$totalPayment  = round($emi * $months, 2);
$totalInterest = round($totalPayment - $loanAmount, 2);

// Success response
echo json_encode([
    "status"  => true,
    "message" => "EMI calculated successfully",
    "data"    => [
        "emi"            => $emi,
        "total_interest" => $totalInterest,
        "total_payment"  => $totalPayment
    ]
]);
