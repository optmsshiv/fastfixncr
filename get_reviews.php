<?php
// Replace with your Google Places API Key and Place ID
$apiKey = "AIzaSyD-pomkT71Z165d7a7m4hR5xA0jlOW2cJA";
$placeId = "ChIJCZp1x_U37jkR0Pozh1A8fAA";

$url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=$placeId&fields=reviews,rating,user_ratings_total&key=$apiKey";

$response = file_get_contents($url);
$data = json_decode($response, true);

$reviews = $data['result']['reviews'] ?? [];

header('Content-Type: application/json');
echo json_encode($reviews);
?>
