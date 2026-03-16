<?php

use OneToMany\LlmSdk\Action\Output\GenerateOutputAction;
use OneToMany\LlmSdk\Action\Query\CompileQueryAction;
use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface as LlmSdkExceptionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\Query\CompileRequest;

/** @var ClientFactory $clientFactory */
$clientFactory = require dirname(__DIR__).'/bootstrap.php';

$model = trim($argv[1] ?? '') ?: 'mock';

try {
    $prompt = 'Write a short summary of the history of PHP.';

    // Build a request of individual query components
    $compileQueryRequest = new CompileRequest($model)->withText($prompt);

    // Compile the query into a request that can be sent to the LLM
    $response = new CompileQueryAction($clientFactory)->act(...[
        'request' => $compileQueryRequest,
    ]);

    // Send the compiled request payload to the LLM server
    $response = new GenerateOutputAction($clientFactory)->act(...[
        'request' => $response->toGenerateOutputRequest(),
    ]);

    successMessage('The model "%s" generated the following output for the prompt "%s":', $response->getModel()->getValue(), $prompt);
    successMessage(sprintf("\n%s", $response->getOutput()));
} catch (LlmSdkExceptionInterface $e) {
    errorMessage($e->getMessage());
}
