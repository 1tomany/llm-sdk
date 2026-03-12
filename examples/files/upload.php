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

$vendor = trim($argv[2] ?? '') ?: 'mock';

try {
    $fileName = basename($filePath);

    // Create a request to upload the file
    $uploadRequest = new UploadRequest($vendor)->atPath($filePath)->withFormat(...[
        'format' => mime_content_type($filePath) ?: 'application/octet-stream',
    ]);

    // Upload the file to the LLM vendor
    $response = new UploadFileAction($clientFactory)->act(...[
        'request' => $uploadRequest,
    ]);

    successMessage('The file "%s" was successfully uploaded to %s with URI "%s".', $fileName, $response->getVendor()->getName(), $response->getUri());

    // Create a request to delete the file
    $deleteRequest = new DeleteRequest($vendor, $response->getUri());

    // Delete the file from the LLM vendor
    $response = new DeleteFileAction($clientFactory)->act(...[
        'request' => $deleteRequest,
    ]);

    successMessage('The file "%s" was successfully deleted from %s.', $fileName, $response->getVendor()->getName());
} catch (LlmSdkExceptionInterface $e) {
    errorMessage($e->getMessage());
}
