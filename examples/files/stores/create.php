<?php

use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface as LlmSdkExceptionInterface;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\Store\CreateStoreRequest;

/** @var ClientFactory $clientFactory */
$clientFactory = require dirname(__DIR__).'/../bootstrap.php';

try {
    $vendor = trim($argv[1] ?? '') ?: 'mock';

    if (!$name = trim($argv[2] ?? '')) {
        throw new InvalidArgumentException(sprintf('Usage: php %s <vendor> <name>', basename(__FILE__)));
    }

    // Create the file search store
    $createStoreRequest = new CreateStoreRequest($vendor, $name);

    // Upload the file to the LLM vendor
    // $response = new UploadFileAction($clientFactory)->act(...[
    //     'request' => $uploadFileRequest,
    // ]);
} catch (LlmSdkExceptionInterface $e) {
    $response = $e;
}

// printf("%s\n", json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
// exit($response instanceof Throwable ? 1 : 0);
