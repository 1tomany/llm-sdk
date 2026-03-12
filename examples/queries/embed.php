<?php

use OneToMany\LlmSdk\Action\Query\CompileQueryAction;
use OneToMany\LlmSdk\Action\Query\EmbedContentAction;
use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface as LlmSdkExceptionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\Query\CompileRequest;

/** @var ClientFactory $clientFactory */
$clientFactory = require dirname(__DIR__).'/bootstrap.php';

$model = trim($argv[1] ?? '') ?: 'mock-embedding';

try {
    $prompt = 'Write a short summary of the history of PHP';

    // Build a request of individual query components
    $compileRequest = new CompileRequest($model)->withPrompt($prompt)->withDimensions(768);

    // Compile the query into a request that can be sent to the LLM
    $response = new CompileQueryAction($clientFactory)->act(...[
        'request' => $compileRequest,
    ]);

    // Send the compiled request payload to the LLM server
    $response = new EmbedContentAction($clientFactory)->act(...[
        'request' => $response->toExecuteRequest(),
    ]);

    print_r($response->getEmbedding());
} catch (LlmSdkExceptionInterface $e) {
    errorMessage($e->getMessage());
}
