# PHP wrapper for Almalio API

# Instalation
```bash
composer require almalio/php-wrapper
```

# Usage
```php
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

// Check if the request was successful, otherwise check the error message, validate data (see rules lower) and try again
```

# Available methods

## __construct(string $apiKey, string $apiUrl = 'https://almalio.com/api/v1')
Create new instance of Almalio API wrapper.

### Parameters
- `string $apiKey` - API key
- `string $apiUrl` - API URL

## addContact(string $siteKey, array $data)
Add contact to the Almalio system.

### Parameters
- `string $siteKey` - Site key
- `array $data` - Contact data
    - `order_number` - Order number (`required|string|between:1,255`)
    - `order_total` - Order total (`required|numeric|min:0`)
    - `order_currency` - Order currency code (`required|string|size:3`) - ISO 4217, e.g. EUR
    - `order_delivery_type` - Order delivery type (`required|integer|in:1,2`) - ENUM, see below
    - `firstname` - First name (`required|string|between:1,255|regex:/^[\p{L} ]+$/u`)
    - `lastname` - Last name (`required|string|between:1,255|regex:/^[\p{L} ]+$/u`)
    - `email` - Email (`required|email|between:1,255`)
    - `phone` - Phone (`required|string|between:1,255`)
    - `street` - Street (`required|string|between:1,255|regex:/^(?=.*\d)(?=.*[a-zA-Z].*[a-zA-Z]).+$/`)
    - `city` - City (`required|string|between:1,255|regex:/^[\p{L} ]+$/u`)
    - `postcode` - Postcode (`required|string|between:1,255`)
    - `country_code` - Country code (`required|string|size:2`) - ISO 3166-1 alpha-2, e.g. SK
    - `testing` - Testing mode (`optional|boolean`)

#### Order delivery type ENUM
- `1` - Delivery to the address
- `2` - Personal pickup

### Responses
#### Validation error
**Status code:** 400
```json
{
  "message": "Validation failed",
  "error": [
    "The street field format is invalid.",
    "The postcode field is required."
  ]
}
```
#### Imported
**Status code:** 200
```json
{
  "message": "Contact imported successfully",
  "success": true
}
```
#### Duplicate
**Status code:** 200
```json
{
  "message": "Contact already imported",
  "success": false
}
```
#### Test successful
**Status code:** 200
```json
{
  "message": "Test successful",
  "success": true,
  "data": {
    "order_number": "12345",
    "order_total": 123.45,
    "order_currency": "EUR",
    "order_delivery_type": 1,
    "firstname": "John",
    "lastname": "Doe",
    "email": "mail@example.org",
    "phone": "+421905000000",
    "street": "Main Street 123",
    "city": "New York",
    "postcode": "12345",
    "country_code": "SK"
  }
}
```
