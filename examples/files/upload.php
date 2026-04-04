<?php

use OneToMany\LlmSdk\Action\File\UploadFileAction;
use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface as LlmSdkExceptionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\File\UploadFileRequest;

/** @var ClientFactory $clientFactory */
$clientFactory = require dirname(__DIR__).'/bootstrap.php';

try {
    $vendor = trim($argv[1] ?? '') ?: 'mock';

    if (!isset($argv[2])) {
        printf("Usage: php %s <vendor> <file-path> [<purpose>]\n", basename(__FILE__));
        exit(1);
    }

    // Create a request to upload the file
    $uploadFileRequest = new UploadFileRequest($vendor, $argv[2])->usingFormat(...[
        'format' => @mime_content_type($argv[2]) ?: null,
    ]);

    if (null !== $purpose = $argv[3] ?? null) {
        $uploadFileRequest->usingPurpose($purpose);
    }

    // Upload the file to the LLM vendor
    $response = new UploadFileAction($clientFactory)->act(...[
        'request' => $uploadFileRequest,
    ]);
} catch (LlmSdkExceptionInterface $e) {
    $response = $e;
}

printf("%s\n", json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
exit($response instanceof Throwable ? 1 : 0);
