<?php

use OneToMany\LlmSdk\Action\Query\CompileQueryAction;
use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface as LlmSdkExceptionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\Query\CompileRequest;

/** @var ClientFactory $clientFactory */
$clientFactory = require dirname(__DIR__).'/bootstrap.php';

$model = trim($argv[1] ?? '') ?: 'mock-embedding';

try {
    $prompt = 'Write a short summary of the history of PHP';

    // First, compile the query to embed
    $compileRequest = new CompileRequest($model)->withPrompt($prompt)->withDimensions(512);

    // Upload the file to the LLM vendor
    $response = new CompileQueryAction($clientFactory)->act(...[
        'request' => $compileRequest,
    ]);

    print_r($response->getRequest());
} catch (LlmSdkExceptionInterface $e) {
    errorMessage($e->getMessage());
}
