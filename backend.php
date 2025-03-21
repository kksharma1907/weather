<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$apiKey = 'f3fb9697346f7c5c17a952fd7a92c8be'; 
$city = isset($_GET['city']) ? urlencode($_GET['city']) : '';

if (!$city) {
    echo json_encode(["error" => "City name is required"]);
    exit;
}

// Fetch current weather data
$apiUrl = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric";
$response = @file_get_contents($apiUrl);

if ($response === FALSE) {
    echo json_encode(["error" => "Failed to fetch weather data"]);
    exit;
}

$data = json_decode($response, true);
if (!isset($data['main'])) {
    echo json_encode(["error" => "Invalid API response"]);
    exit;
}

// Fetch 5-day forecast data
$forecastUrl = "https://api.openweathermap.org/data/2.5/forecast?q={$city}&appid={$apiKey}&units=metric";
$forecastResponse = @file_get_contents($forecastUrl);

if ($forecastResponse === FALSE) {
    echo json_encode(["error" => "Failed to fetch forecast data"]);
    exit;
}

$forecastData = json_decode($forecastResponse, true);
$forecast = [];

if (isset($forecastData['list'])) {
    foreach ($forecastData['list'] as $item) {
        if (strpos($item['dt_txt'], '12:00:00') !== false) {
            $forecast[] = [
                "date" => $item['dt_txt'],
                "temp" => $item['main']['temp'],
                "desc" => $item['weather'][0]['description']
            ];
        }
    }
}

echo json_encode([
    "city" => $data['name'],
    "temperature" => $data['main']['temp'],
    "humidity" => $data['main']['humidity'],
    "description" => $data['weather'][0]['description'],
    "forecast" => $forecast
]);
?>
