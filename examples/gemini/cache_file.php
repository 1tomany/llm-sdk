<?php

use OneToMany\AI\Client\Gemini\FileClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

require_once __DIR__.'/../../vendor/autoload.php';

$keyVar = 'GEMINI_API_KEY';

if (!$googApiKey = getenv($keyVar)) {
    printf("Set the %s environment variable to continue.\n", $keyVar);
    exit(1);
}

if (!isset($argv[1]) || !is_string($argv[1])) {
    printf("Usage: php %s <file-path>\n", basename(__FILE__));
    exit(1);
}

$path = realpath($argv[1]);

if (!is_file($path) || !is_readable($path)) {
    printf("The file '%s' is not a file or not readable.\n", $path);
    exit(1);
}

$httpClient = HttpClient::create([
    'headers' => [
        'accept' => 'application/json',
        'x-goog-api-key' => $googApiKey,
    ],
]);

$fileClient = new FileClient($httpClient, new ObjectNormalizer());
