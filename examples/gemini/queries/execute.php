<?php

use OneToMany\LlmSdk\Client\Gemini\GeminiClient;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

require_once __DIR__.'/../../common/functions.php';

$apiKey = read_api_key('GEMINI_API_KEY');

/** @var DenormalizerInterface $serializer */
$serializer = require __DIR__.'/../../serializer.php';

// Determine the Gemini model to use
$model = read_model_name('gemini-2.5-flash');

// Create a client that will be used to compile and send queries
$geminiClient = new GeminiClient($serializer, HttpClient::create(), $apiKey);

// First, compile the query: this is not strictly necessary,
// but you can inspect the request that will be sent to the LLM
$compileRequest = new CompileRequest($model)->withPrompt(...[
    'prompt' => 'Who invented the PHP programming language?',
]);

$response = $geminiClient->queries()->compile(...[
    'request' => $compileRequest,
]);

// Now that the query is compiled, create a request to send it to the LLM
$executeRequest = new ExecuteRequest($model)->withUrl($response->getUrl())->withRequest($response->getRequest());

// Note: The `CompileResponse` object returned from
// `compile()` has a handy method which does the same
// work above but as a single method call:
//
//     $executeRequest = $response->toExecuteRequest();

// Send the request to Gemini
$response = $geminiClient->queries()->execute(...[
    'request' => $executeRequest,
]);

printf("The model '%s' generated the following output in %dms:\n\n%s\n", $response->getModel(), $response->getRuntime(), $response->getOutput());
