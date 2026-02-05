<?php

use OneToMany\AI\Client\OpenAi\FileClient;
use OneToMany\AI\Request\File\DeleteRequest;
use Symfony\Component\HttpClient\HttpClient;

require_once __DIR__.'/../../../vendor/autoload.php';

$apiKeyName = 'OPENAI_API_KEY';

if (!$apiKey = getenv($apiKeyName)) {
    printf("Set the '%s' environment variable to continue.\n", $apiKeyName);
    exit(1);
}

$fileClient = new FileClient(HttpClient::create(), $apiKey);
print_r($fileClient->delete(new DeleteRequest('gpt-5.1', 'file-7kxcDx7KxCu1GLobsjvaYK')));
