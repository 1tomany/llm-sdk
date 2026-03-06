<?php

use OneToMany\LlmSdk\Action\File\DeleteFileAction;
use OneToMany\LlmSdk\Action\File\UploadFileAction;
use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;

/** @var ClientFactory $clientFactory */
$clientFactory = require dirname(__DIR__).'/bootstrap.php';

if ($argc < 2) {
    printUsage();
}

$path = trim($argv[1]);

if (!is_file($path)) {
    printUsage();
}

$model = strtolower($argv[2]);

try {
    echo sprintf('Uploading the file "%s" to the model "%s".', basename($path), $model).PHP_EOL;

    // Create a request to upload the file
    $uploadRequest = new UploadRequest($model)->atPath($path);

    // Upload the file to the LLM vendor
    $response = new UploadFileAction($clientFactory)->act(...[
        'request' => $uploadRequest,
    ]);

    printf("The file '%s' was successfully uploaded to the model '%s' with URI '%s'.\n", basename($path), $response->getModel(), $response->getUri());

    // Create a request to delete the file
    $deleteRequest = new DeleteRequest($model, $response->getUri());

    // Delete the file from the LLM vendor
    $response = new DeleteFileAction($clientFactory)->act(...[
        'request' => $deleteRequest,
    ]);

    printf("The file '%s' was successfully deleted from the model '%s'.\n", basename($path), $response->getModel());
} catch (ExceptionInterface $e) {
    printf("[ERROR] %s\n", $e->getMessage());
    exit(1);
}

function printUsage(): never
{
    printf("Usage: php %s <path> <model>\n", basename(__FILE__));
    exit(1);
}
