<?php
include_once '../vendor/autoload.php';

use GuzzleHttp\Client as HttpClient;


$client = new HttpClient([
    'base_uri' => "http://firstapp",
    'timeout' => 2.0,
    'headers' => [
        'Content-Type' => 'application/json',
    ]
]);

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    $response = $client->delete("/api/user/" . $userId);
    if ($response->getStatusCode() == 200) {
        header("Location: index.php");
    }
}

?>
