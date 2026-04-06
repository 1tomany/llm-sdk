<?php

use OneToMany\LlmSdk\Action\SearchStore\ReadSearchStoreAction;
use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface as LlmSdkExceptionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\SearchStore\ReadSearchStoreRequest;

/** @var ClientFactory $clientFactory */
$clientFactory = require dirname(__DIR__).'/bootstrap.php';

try {
    $vendor = trim($argv[1] ?? '') ?: 'mock';

    if (empty($argv[2] ?? null)) {
        printf("Usage: php %s <vendor> <search-store-uri>\n", basename(__FILE__));
        exit(1);
    }

    // Read the search store
    $response = new ReadSearchStoreAction($clientFactory)->act(...[
        'request' => new ReadSearchStoreRequest($vendor, $argv[2]),
    ]);
} catch (LlmSdkExceptionInterface $e) {
    $response = $e;
}

printf("%s\n", json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
exit($response instanceof Throwable ? 1 : 0);
