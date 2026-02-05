<?php

use OneToMany\AI\Client\OpenAi\FileClient;
use OneToMany\AI\Contract\Exception\ExceptionInterface as AiExceptionInterface;
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
    /** @var non-empty-string $path */
    $path = realpath(__DIR__.'/../../data/label.jpeg');
}

// Create the OpenAI FileClient
$fileClient = new FileClient(HttpClient::create(), $apiKey);

try {
    // Upload the file
    $response = $fileClient->upload(new UploadRequest($model)->atPath($path));

    printf("File uploaded successfully!\n");
    printf("  Model:   %s\n", $response->getModel());
    printf("  URI:     %s\n", $response->getUri());
    printf("  Name:    %s\n", $response->getName());
    printf("  Purpose: %s\n", $response->getPurpose());
    printf("%s\n", str_repeat('-', 60));

    // Delete the file
    $response = $fileClient->delete(new DeleteRequest($model, $response->getUri()));

    printf("File deleted successfully!\n");
    printf("  Model:   %s\n", $response->getModel());
    printf("  URI:     %s\n", $response->getUri());
    printf("%s\n", str_repeat('-', 60));
} catch (AiExceptionInterface $e) {
    printf("[ERROR(%d)] %s\n", $e->getCode(), $e->getMessage());
}
