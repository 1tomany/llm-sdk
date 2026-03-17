<?php

use OneToMany\LlmSdk\Action\File\UploadFileAction;
use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface as LlmSdkExceptionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\File\UploadFileRequest;

/** @var ClientFactory $clientFactory */
$clientFactory = require dirname(__DIR__).'/bootstrap.php';

if (!$path = trim($argv[1] ?? '')) {
    errorMessage('Usage: php %s <path> <vendor>', basename(__FILE__));
}

$vendor = trim($argv[2] ?? '') ?: 'mock';

try {
    // Create a request to upload the file
    $uploadFileRequest = new UploadFileRequest($vendor, $path);

    if ($format = mime_content_type($path)) {
        $uploadFileRequest->withFormat($format);
    }

    // Upload the file to the LLM vendor
    $response = new UploadFileAction($clientFactory)->act(...[
        'request' => $uploadFileRequest,
    ]);

    successMessage('The file "%s" was successfully uploaded to %s with URI "%s".', basename($path), $response->getVendor()->getName(), $response->getUri());
} catch (LlmSdkExceptionInterface $e) {
    errorMessage($e->getMessage());
}
