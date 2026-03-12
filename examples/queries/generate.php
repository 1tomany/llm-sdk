<?php

use OneToMany\LlmSdk\Action\Query\CompileQueryAction;
use OneToMany\LlmSdk\Action\Query\ExecuteQueryAction;
use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface as LlmSdkExceptionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Response\Query\Content\GenerateResponse;

/** @var ClientFactory $clientFactory */
$clientFactory = require dirname(__DIR__).'/bootstrap.php';

$model = trim($argv[1] ?? '') ?: 'mock';

try {
    $prompt = 'Write a short summary of the history of PHP';

    // Build a request of individual query components
    $compileRequest = new CompileRequest($model)->withPrompt($prompt);

    // Compile the query into a request that can be sent to the LLM
    $response = new CompileQueryAction($clientFactory)->act(...[
        'request' => $compileRequest,
    ]);

    // Now that the query is compiled, build a request to send it to the LLM. Note: You can call
    // $response->toExecuteRequest() to automatically build the same object as the steps below.
    $executeRequest = new ExecuteRequest($model)->withUrl($response->getUrl())->withRequest(...[
        'request' => $response->getRequest(),
    ]);

    /** @var GenerateResponse $response */
    $response = new ExecuteQueryAction($clientFactory)->act(...[
        'request' => $executeRequest,
    ]);

    successMessage('The model "%s" generated the following output for the prompt "%s":', $response->getModel()->getValue(), $prompt);
    successMessage(sprintf("\n%s", $response->getOutput()));
} catch (LlmSdkExceptionInterface $e) {
    errorMessage($e->getMessage());
}
