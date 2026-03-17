<?php

use OneToMany\LlmSdk\Action\Embedding\CreateEmbeddingAction;
use OneToMany\LlmSdk\Action\Query\CompileQueryAction;
use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface as LlmSdkExceptionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;

/** @var ClientFactory $clientFactory */
$clientFactory = require dirname(__DIR__).'/bootstrap.php';

$model = trim($argv[1] ?? '') ?: 'mock-embedding';

try {
    $prompt = trim($argv[2] ?? '') ?: 'Write a short summary of the history of PHP.';

    // Build a request of individual query components
    $compileQueryRequest = new CompileQueryRequest($model)->withPrompt($prompt)->usingDimensions(768);

    // Compile the query into a request that can be sent to the LLM
    $response = new CompileQueryAction($clientFactory)->act(...[
        'request' => $compileQueryRequest,
    ]);

    // Send the compiled request payload to the LLM server
    $response = new CreateEmbeddingAction($clientFactory)->act(...[
        'request' => $response->toCreateEmbeddingRequest(),
    ]);

    $embedding = $response->getEmbedding();

    printf("The model '%s' generated an embedding with %d %s: [%.8f ... %.8f].\n", $response->getModel()->getValue(), $response->getDimensions(), 1 === $response->getDimensions() ? 'dimension' : 'dimensions', $embedding[array_key_first($embedding)], $embedding[array_key_last($embedding)]);
} catch (LlmSdkExceptionInterface $e) {
    errorMessage($e->getMessage());
}
