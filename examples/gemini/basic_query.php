<?php

use OneToMany\AI\Client\Gemini\QueryClient;
use OneToMany\AI\Request\Query\CompileRequest;
use OneToMany\AI\Request\Query\ExecuteRequest;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

require_once __DIR__.'/../common/functions.php';

$apiKey = read_api_key('GEMINI_API_KEY');

/** @var DenormalizerInterface $serializer */
$serializer = require __DIR__.'/../serializer.php';

$httpClient = HttpClient::create([
    'timeout' => 120.0,
]);

// Determine the Gemini model to use
$geminiModel = read_model_name('gemini-2.5-flash');

// Create a client that will be used to compile and send queries
$queryClient = new QueryClient($serializer, $httpClient, $apiKey);

// First, compile the query: this isn't strictly necessary, but by doing so,
// you can log or otherwise modify the request that will be sent to the LLM.
$compileRequest = new CompileRequest($geminiModel)->withText(...[
    'text' => 'Who invented the PHP programming language?',
]);

$response = $queryClient->compile(...[
    'request' => $compileRequest,
]);

// Now that the query is compiled, create
// a request to send it to the LLM server
$executeRequest = new ExecuteRequest(...[
    'model' => $geminiModel,
]);

$executeRequest->withUrl($response->getUrl())->withRequest(...[
    'request' => $response->getRequest(),
]);

// Note: The `CompileResponse` object returned from
// the `compile()` call has a handy method which does
// the same work above but as a single method call:
//
//     $executeRequest = $response->toExecuteRequest();

// Finally, execute the query with the model
$response = $queryClient->execute($executeRequest);

printf("The model '%s' generated the following output in %dms:\n\n%s\n", $response->getModel(), $response->getRuntime(), $response->getOutput());
