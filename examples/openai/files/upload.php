<?php

use OneToMany\AI\Client\OpenAi\FileClient;
use OneToMany\AI\Request\File\DeleteRequest;
use OneToMany\AI\Request\File\UploadRequest;
use Symfony\Component\HttpClient\HttpClient;

require_once __DIR__.'/../../../vendor/autoload.php';

$apiKeyName = 'OPENAI_API_KEY';

if (!$apiKey = getenv($apiKeyName)) {
    printf("Set the '%s' environment variable to continue.\n", $apiKeyName);
    exit(1);
}

$model = getenv('OPENAI_MODEL') ?: 'gpt-5.1';

if (false === is_file($path = $argv[1] ?? '')) {
    $path = realpath(__DIR__.'/../../data/label.jpeg');
}

// if (!$format = mime_content_type())

// Create the OpenAI FileClient
$fileClient = new FileClient(HttpClient::create(), $apiKey);

// Create the request to upload a file
$uploadRequest = new UploadRequest($model)->atPath($path)->withFormat(...[
    'format' => mime_content_type($path) ?: null,
]);
// ->withFormat()
// ->withPurpose('user_data');

print_r($uploadRequest);
// print_r($fileClient->delete(new DeleteRequest($model, 'file-7kxcDx7KxCu1GLobsjvaYK')));
