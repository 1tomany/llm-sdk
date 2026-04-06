<?php

use OneToMany\LlmSdk\Action\SearchStore\CreateStoreAction;
use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface as LlmSdkExceptionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\SearchStore\CreateSearchStoreRequest;

/** @var ClientFactory $clientFactory */
$clientFactory = require dirname(__DIR__).'/bootstrap.php';

try {
    $vendor = trim($argv[1] ?? '') ?: 'mock';

    if (!$name = trim($argv[2] ?? '')) {
        printf("Usage: php %s <vendor> <search-store-name>\n", basename(__FILE__));
        exit(1);
    }

    // Create the search store
    $response = new CreateStoreAction($clientFactory)->act(...[
        'request' => new CreateSearchStoreRequest($vendor, $name),
    ]);
} catch (LlmSdkExceptionInterface $e) {
    $response = $e;
}

printf("%s\n", json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
exit($response instanceof Throwable ? 1 : 0);
