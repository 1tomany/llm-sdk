<?php

use OneToMany\AI\Client\Gemini\FileClient;
use OneToMany\AI\Contract\Exception\ExceptionInterface as AiExceptionInterface;
use OneToMany\AI\Request\File\CacheFileRequest;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

require_once __DIR__.'/../bootstrap.php';

assert(isset($serializer) && $serializer instanceof DenormalizerInterface);

$keyVar = 'GEMINI_API_KEY';

if (!$googApiKey = getenv($keyVar)) {
    printf("Set the %s environment variable to continue.\n", $keyVar);
    exit(1);
}

if (!is_string($argv[1] ?? null)) {
    printf("Usage: php %s <file-path>\n", basename(__FILE__));
    exit(1);
}

$path = realpath($argv[1]);

if (!$path || !is_file($path) || !is_readable($path)) {
    printf("The file '%s' is not a file or not readable.\n", $path);
    exit(1);
}

// Construct the Symfony HTTP Client
$httpClient = HttpClient::create([
    'headers' => [
        'accept' => 'application/json',
        'x-goog-api-key' => $googApiKey,
    ],
]);

// Construct the Gemini file client
$fileClient = new FileClient($httpClient, $serializer);

try {
    // Cache the file with Gemini
    $cachedFile = $fileClient->cache(CacheFileRequest::create('gemini', $path));
} catch (AiExceptionInterface $e) {
    printf("[ERROR] %s\n", $e->getMessage());
    exit(1);
}

printf("Vendor:    %s\n", $cachedFile->getVendor());
printf("URI:       %s\n", $cachedFile->getUri());
printf("Name:      %s\n", $cachedFile->getName());
printf("ExpiresAt: %s\n", $cachedFile->getExpiresAt()?->format('c'));
