<?php

require_once __DIR__ . '/../src/Almalio.php';

$api = new Almalio('API_KEY');
$result = $api->addContact('SITE_KEY', [
    'order_number' => '12345',
    'order_total' => 123.45,
    'order_currency' => 'EUR',
    'order_delivery_type' => 1,
    'firstname' => 'John',
    'lastname' => 'Doe',
    'email' => 'mail@example.org',
    'phone' => '+421905000000',
    'street' => 'Main Street 123',
    'city' => 'New York',
    'postcode' => '12345',
    'country_code' => 'SK',
]);

var_dump($result);
