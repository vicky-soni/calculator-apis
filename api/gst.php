<?php
header("Content-Type: application/json");

// Read JSON input
$input = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (!isset($input['country_code']) || !isset($input['amount'])) {
    echo json_encode([
        "status" => false,
        "message" => "country_code and amount are required"
    ]);
    exit;
}

$countryCode = strtoupper(trim($input['country_code']));
$amount      = $input['amount'];

// Validate amount
if (!is_numeric($amount) || $amount < 0) {
    echo json_encode([
        "status" => false,
        "message" => "Invalid amount"
    ]);
    exit;
}

// Associative array: Country Code => GST Rate
$gstRates = [
    "IN" => 18,  // India
    "AU" => 10,  // Australia
    "NZ" => 15,  // New Zealand
    "SG" => 8,   // Singapore
    "CA" => 5,   // Canada
    "GB" => 20,  // United Kingdom (VAT)
    "DE" => 19,  // Germany (VAT)
    "FR" => 20,  // France (VAT)
    "JP" => 10,  // Japan
    "ZA" => 15   // South Africa
];

// Check if country supported
if (!array_key_exists($countryCode, $gstRates)) {
    echo json_encode([
        "status" => false,
        "message" => "GST rate not available for this country"
    ]);
    exit;
}

// Get tax rate
$taxRate = $gstRates[$countryCode];

// Calculate GST
$taxAmount   = round(($amount * $taxRate) / 100, 2);
$totalAmount = round($amount + $taxAmount, 2);

// Success response
echo json_encode([
    "status"  => true,
    "message" => "GST calculated successfully",
    "data"    => [
        "country_code" => $countryCode,
        "tax_rate"     => $taxRate,
        "tax_amount"   => $taxAmount,
        "total_amount" => $totalAmount
    ]
]);
