<?php

use OneToMany\LlmSdk\Client\OpenAi\OpenAiClient;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

require_once __DIR__.'/../../common/functions.php';

$apiKey = read_api_key('OPENAI_API_KEY');

/** @var DenormalizerInterface $serializer */
$serializer = require __DIR__.'/../../serializer.php';

$filePath = $argv[1] ?? null;

if (!$filePath || !is_file($filePath)) {
    printf("Usage: php %s <file-path>\n", basename(__FILE__));
    exit(1);
}

// Determine the OpenAI model to use
$model = read_model_name('gpt-5-mini');

// Create the client to upload and delete files
$openAiClient = new OpenAiClient($serializer, HttpClient::create(), $apiKey);

// Create a request to upload a file
$uploadRequest = new UploadRequest($model)->atPath($filePath)->withPurpose('user_data');

// Upload the file to OpenAI
$response = $openAiClient->files()->upload(...[
    'request' => $uploadRequest,
]);

printf("File '%s' uploaded with URI '%s'.\n", basename($filePath), $response->getUri());

// Create a request to delete the file
$deleteRequest = new DeleteRequest($model, $response->getUri());

// Delete the file from OpenAI
$response = $openAiClient->files()->delete(...[
    'request' => $deleteRequest,
]);

printf("File URI '%s' successfully deleted.\n", $response->getUri());
