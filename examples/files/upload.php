<?php

use OneToMany\LlmSdk\Action\File\DeleteFileAction;
use OneToMany\LlmSdk\Action\File\UploadFileAction;
use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface as LlmSdkExceptionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;

/** @var ClientFactory $clientFactory */
$clientFactory = require dirname(__DIR__).'/bootstrap.php';

$filePath = trim($argv[1] ?? '');

if (!is_file($filePath)) {
    errorMessage('Usage: php %s <file-path> <model>', basename(__FILE__));
}

$model = strtolower($argv[2] ?? 'mock');

try {
    $fileName = basename($filePath);

    // Create a request to upload the file
    $uploadRequest = new UploadRequest($model)->atPath($filePath);

    // Upload the file to the LLM vendor
    $response = new UploadFileAction($clientFactory)->act(...[
        'request' => $uploadRequest,
    ]);

    successMessage('The file "%s" was successfully uploaded to the model "%s" with URI "%s".', $fileName, $response->getModel(), $response->getUri());

    // Create a request to delete the file
    $deleteRequest = new DeleteRequest($model, $response->getUri());

    // Delete the file from the LLM vendor
    $response = new DeleteFileAction($clientFactory)->act(...[
        'request' => $deleteRequest,
    ]);

    successMessage('The file "%s" was successfully deleted from the model "%s".', $fileName, $response->getModel());
} catch (LlmSdkExceptionInterface $e) {
    errorMessage($e->getMessage());
}
