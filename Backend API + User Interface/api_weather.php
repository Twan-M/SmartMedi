<?php
// Haal de stad op uit de query parameter
$city = isset($_GET['city']) ? strtolower($_GET['city']) : 'veenendaal';

// Stel de URL in op basis van de opgegeven stad
switch ($city) {
    case 'amsterdam':
        $weatherUrl = "https://www.buienalarm.nl/nederland/amsterdam/5575";
        break;
    case 'veenendaal':
        $weatherUrl = "https://www.buienalarm.nl/nederland/veenendaal/19509";
        break;
    default:
        http_response_code(400);
        echo json_encode(array("error" => "Ongeldige stad"));
        exit();
}

// Haal de data van de weerwebsite op
$response = file_get_contents($weatherUrl);

// Controleer of de response succesvol was
if ($response === FALSE) {
    http_response_code(500);
    echo json_encode(array("error" => "Kon de weerdata niet ophalen."));
    exit();
}

// Zoek naar de JavaScript-variabele met de JSON data
$pattern = '/var weatherData = (\[.*?\]);/';
preg_match($pattern, $response, $matches);

// Controleer of de JSON data is gevonden
if (isset($matches[1])) {
    // Haal de JSON-string op en decodeer het
    $jsonString = $matches[1];
    $weatherData = json_decode($jsonString, true);

    // Controleer op JSON parsing errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(500);
        echo json_encode(array("error" => "JSON parsing mislukt."));
        exit();
    }

    // Geef de JSON data terug
    header('Content-Type: application/json');
    echo json_encode($weatherData);
} else {
    http_response_code(500);
    echo json_encode(array("error" => "Weerdata niet gevonden in de response."));
}
?>
