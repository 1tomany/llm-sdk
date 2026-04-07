<?php

use OneToMany\LlmSdk\Action\Embedding\CreateEmbeddingAction;
use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface as LlmSdkExceptionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;

/** @var ClientFactory $clientFactory */
$clientFactory = require dirname(__DIR__).'/bootstrap.php';

$model = trim($argv[1] ?? '') ?: 'mock-embedding';

try {
    $prompt = trim($argv[2] ?? '') ?: 'Write a short summary of the history of PHP.';

    // Compile a query comprised of individual components
    $compileQueryRequest = new CompileQueryRequest($model)->withPrompt($prompt)->usingDimensions(768);

    // Compile the query and send it to the LLEM server
    $response = new CreateEmbeddingAction($clientFactory)->act(...[
        'request' => $compileQueryRequest,
    ]);
} catch (LlmSdkExceptionInterface $e) {
    $response = $e;
}

printf("%s\n", json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
exit($response instanceof Throwable ? 1 : 0);
