<?php

use OneToMany\LlmSdk\Client\Anthropic\AnthropicClient;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

require_once __DIR__.'/../../common/functions.php';

$apiKey = read_api_key('ANTHROPIC_API_KEY');

/** @var DenormalizerInterface $serializer */
$serializer = require __DIR__.'/../../serializer.php';

$filePath = $argv[1] ?? null;

if (!$filePath || !is_file($filePath)) {
    printf("Usage: php %s <file-path>\n", basename(__FILE__));
    exit(1);
}

$httpClient = HttpClient::create([
    'timeout' => 120.0,
]);

// Determine the Anthropic model to use
$model = read_model_name('claude-opus-4-6');

// Create the client to upload and delete files
$anthropicClient = new AnthropicClient($serializer, $httpClient, $apiKey);

// Create a request to upload a file
$uploadRequest = new UploadRequest($model)->atPath($filePath);

// Upload the file to Anthropic
$response = $anthropicClient->files()->upload(...[
    'request' => $uploadRequest,
]);

printf("File '%s' uploaded with URI '%s'.\n", basename($filePath), $response->getUri());

// Create a request to delete the file
$deleteRequest = new DeleteRequest($model, $response->getUri());

// Delete the file from Anthropic
$response = $anthropicClient->files()->delete(...[
    'request' => $deleteRequest,
]);

printf("File URI '%s' successfully deleted.\n", $response->getUri());
