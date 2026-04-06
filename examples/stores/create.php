<?php

use OneToMany\LlmSdk\Action\Store\CreateStoreAction;
use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface as LlmSdkExceptionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\SearchStore\CreateSearchStoreRequest;

/** @var ClientFactory $clientFactory */
$clientFactory = require dirname(__DIR__).'/bootstrap.php';

try {
    $vendor = trim($argv[1] ?? '') ?: 'mock';

    if (!$name = trim($argv[2] ?? '')) {
        printf("Usage: php %s <vendor> <store-name>\n", basename(__FILE__));
        exit(1);
    }

    // Create the search store request
    $createSearchStoreRequest = new CreateSearchStoreRequest($vendor, $name);

    // Create the search store
    $response = new CreateStoreAction($clientFactory)->act(...[
        'request' => $createSearchStoreRequest,
    ]);
} catch (LlmSdkExceptionInterface $e) {
    $response = $e;
}

printf("%s\n", json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
exit($response instanceof Throwable ? 1 : 0);
