<?php

use OneToMany\LlmSdk\Action\SearchStore\ImportFileAction;
use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface as LlmSdkExceptionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\SearchStore\ImportUploadedFileRequest;

/** @var ClientFactory $clientFactory */
$clientFactory = require dirname(__DIR__).'/bootstrap.php';

try {
    $vendor = trim($argv[1] ?? '') ?: 'mock';

    if (empty($argv[2] ?? null) || empty($argv[3] ?? null)) {
        printf("Usage: php %s <vendor> <search-store-uri> <file-uri>\n", basename(__FILE__));
        exit(1);
    }

    // Create a request to import the file to the search store
    $importUploadedFileRequest = new ImportUploadedFileRequest($vendor, $argv[2], $argv[1]);

    // Import the file to the search store
    $response = new ImportFileAction($clientFactory)->act(...[
        'request' => $importUploadedFileRequest,
    ]);
} catch (LlmSdkExceptionInterface $e) {
    $response = $e;
}

printf("%s\n", json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
exit($response instanceof Throwable ? 1 : 0);
