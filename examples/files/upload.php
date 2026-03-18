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
    $uploadFileRequest = new UploadFileRequest($vendor, $path)->usingPurpose($argv[3] ?? null);

    if ($format = @mime_content_type($path)) {
        $uploadFileRequest->usingFormat($format);
    }

    // Upload the file to the LLM vendor
    $response = new UploadFileAction($clientFactory)->act(...[
        'request' => $uploadFileRequest,
    ]);
} catch (LlmSdkExceptionInterface $e) {
    $response = $e;
}

printf("%s\n", json_encode($response, JSON_PRETTY_PRINT));
exit($response instanceof Throwable ? 1 : 0);
