<?php

use OneToMany\LlmSdk\Action\File\DeleteFileAction;
use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface as LlmSdkExceptionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\File\DeleteFileRequest;

/** @var ClientFactory $clientFactory */
$clientFactory = require dirname(__DIR__).'/bootstrap.php';

try {
    $vendor = trim($argv[1] ?? '') ?: 'mock';

    if (!$fileUri = trim($argv[2] ?? '')) {
        printf("Usage: php %s <vendor> <file-uri>\n", basename(__FILE__));
        exit(1);
    }

    // Create a request to delete the file
    $deleteFileRequest = new DeleteFileRequest($vendor, $fileUri);

    // Delete the file from the LLM vendor
    $response = new DeleteFileAction($clientFactory)->act(...[
        'request' => $deleteFileRequest,
    ]);
} catch (LlmSdkExceptionInterface $e) {
    $response = $e;
}

printf("%s\n", json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
exit($response instanceof Throwable ? 1 : 0);
