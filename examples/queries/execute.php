<?php

use OneToMany\LlmSdk\Action\Query\CompileQueryAction;
use OneToMany\LlmSdk\Action\Query\ExecuteQueryAction;
use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface as LlmSdkExceptionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Response\Query\GenerateResponse;

/** @var ClientFactory $clientFactory */
$clientFactory = require dirname(__DIR__).'/bootstrap.php';

$model = trim($argv[1] ?? '') ?: 'mock';

try {
    $prompt = 'Write a short summary of the history of PHP';

    // First, compile the query: this is not strictly necessary,
    // but you can inspect the request that will be sent to the LLM
    $compileRequest = new CompileRequest($model)->withPrompt($prompt);

    // Upload the file to the LLM vendor
    $response = new CompileQueryAction($clientFactory)->act(...[
        'request' => $compileRequest,
    ]);

    // Now that the query is compiled, create a request to send it to the LLM
    $executeRequest = new ExecuteRequest($model)->withUrl($response->getUrl())->withRequest($response->getRequest());

    // Note: The `CompileResponse` object returned from
    // `compile()` has a handy method which does the same
    // work above but as a single method call:
    //
    //     $executeRequest = $response->toExecuteRequest();

    /** @var GenerateResponse $response */
    $response = new ExecuteQueryAction($clientFactory)->act(...[
        'request' => $executeRequest,
    ]);

    successMessage('The model "%s" generated the following output for the prompt "%s":', $response->getModel()->getValue(), $prompt);
    successMessage(sprintf("\n%s", $response->getOutput()));
} catch (LlmSdkExceptionInterface $e) {
    errorMessage($e->getMessage());
}
