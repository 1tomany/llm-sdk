<?php

use OneToMany\LlmSdk\Action\File\DeleteFileAction;
use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface as LlmSdkExceptionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\File\DeleteRequest;

/** @var ClientFactory $clientFactory */
$clientFactory = require dirname(__DIR__).'/bootstrap.php';

if (!$uri = trim($argv[1] ?? '')) {
    errorMessage('Usage: php %s <file-uri> <vendor>', basename(__FILE__));
}

$vendor = trim($argv[2] ?? '') ?: 'mock';

try {
    // Create a request to delete the file
    $deleteFileRequest = new DeleteRequest($vendor)->usingUri($uri);

    // Delete the file from the LLM vendor
    $response = new DeleteFileAction($clientFactory)->act(...[
        'request' => $deleteFileRequest,
    ]);

    successMessage('The file "%s" was successfully deleted from %s.', $response->getUri(), $response->getVendor()->getName());
} catch (LlmSdkExceptionInterface $e) {
    errorMessage($e->getMessage());
}
