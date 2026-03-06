<?php

use OneToMany\LlmSdk\Contract\Factory\ClientFactoryInterface;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;

/** @var ClientFactoryInterface $clientFactory */
$clientFactory = require dirname(dirname(__DIR__)).'/bootstrap.php';

if ($argc < 2) {
    printUsage();
}

$path = trim($argv[1]);

if (!is_file($path)) {
    printUsage();
}

$model = $argv[2] ?? 'claude-opus-4.6';

// Determine the Anthropic model to use
// $model = read_model_name('claude-opus-4-6');

// Create the client to upload and delete files
// $anthropicClient = new AnthropicClient($serializer, HttpClient::create(), $apiKey);

// Create a request to upload a file
$uploadRequest = new UploadRequest($model)->atPath($path);

// Upload the file to Anthropic
$response = $clientFactory->create($model)->files()->upload(...[
    'request' => $uploadRequest,
]);

printf("File '%s' uploaded with URI '%s'.\n", basename($path), $response->getUri());

// Create a request to delete the file
$deleteRequest = new DeleteRequest($model, $response->getUri());

// Delete the file from Anthropic
$response = $anthropicClient->files()->delete(...[
    'request' => $deleteRequest,
]);

printf("File URI '%s' successfully deleted.\n", $response->getUri());

function printUsage(): never
{
    printf("Usage: php %s <file> <model>\n", basename(__FILE__));
    exit(1);
}
