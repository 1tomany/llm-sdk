<?php

use OneToMany\LlmSdk\Action\File\UploadFileAction;
use OneToMany\LlmSdk\Action\Store\ImportFileAction;
use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface as LlmSdkExceptionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\File\UploadFileRequest;
use OneToMany\LlmSdk\Request\SearchStore\ImportSearchStoreFileRequest;

/** @var ClientFactory $clientFactory */
$clientFactory = require dirname(__DIR__).'/../bootstrap.php';

try {
    $vendor = trim($argv[1] ?? '') ?: 'mock';

    if (!isset($argv[2]) || !isset($argv[3])) {
        printf("Usage: php %s <vendor> <store-uri> <file-path>\n", basename(__FILE__));
        exit(1);
    }

    // Create a request to upload the file
    $uploadFileRequest = new UploadFileRequest($vendor, $argv[3])->usingFormat(...[
        'format' => @mime_content_type($argv[3]) ?: null,
    ]);

    // Upload the file to the LLM vendor
    $response = new UploadFileAction($clientFactory)->act(...[
        'request' => $uploadFileRequest,
    ]);

    // Create a request to import the file to the search store
    $importSearchStoreFileRequest = new ImportSearchStoreFileRequest($vendor, $argv[2], $response->getUri())->usingFileName(...[
        'fileName' => $response->getName(),
    ]);

    // Import the file to the search store
    $response = new ImportFileAction($clientFactory)->act(...[
        'request' => $importSearchStoreFileRequest,
    ]);
} catch (LlmSdkExceptionInterface $e) {
    $response = $e;
}

printf("%s\n", json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
exit($response instanceof Throwable ? 1 : 0);
