<?php

class Almalio
{
    private string $apiKey;
    private string $serverUrl;

    public function __construct(string $apiKey, string $serverUrl = 'https://almalio.com/api/v1')
    {
        $this->apiKey = $apiKey;
        $this->serverUrl = $serverUrl;
    }

    public function addContact(string $siteKey, array $data)
    {
        return $this->post($siteKey, 'import/contact', $data);
    }

    private function post(string $siteKey, string $request, array $data, array $query = [])
    {
        return $this->send($siteKey, $request, $data, 'post', $query);
    }

    private function send(string $siteKey, string $request, $data, $method, array $query)
    {

        $urlRequest = $this->serverUrl . '/' . $request;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlRequest);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (!is_null($method)) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        }

        $json_options = 0 | (PHP_VERSION_ID >= 70300 ? JSON_THROW_ON_ERROR : 0);

        if (is_array($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, $json_options));
        }

        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'apikey: ' . $this->apiKey,
                'sitekey: ' . $siteKey,
                'Content-Type: application/json'
            )
        );

        $raw_output = $output = curl_exec($ch);

        if (!curl_errno($ch)) {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            $error_message_is_json = $content_type === 'application/json';
            if ($error_message_is_json) {
                $output = json_decode($raw_output, null, 512, $json_options);
            }
            if ($http_code < 200 || $http_code > 299) {
                return array(
                    'error' => $http_code,
                    'message' => $output,
                );
            }
        }

        curl_close($ch);

        if (is_array(json_decode($raw_output, true))) {
            $raw_output = json_decode($raw_output);
        }

        return $raw_output;
    }
}
