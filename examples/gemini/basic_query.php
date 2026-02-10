<?php

require_once __DIR__.'/../../vendor/autoload.php';

use OneToMany\AI\Client\Gemini\QueryClient;
use OneToMany\AI\Request\Query\CompileRequest;
use OneToMany\AI\Request\Query\ExecuteRequest;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\PropertyInfo\Extractor\ConstructorExtractor;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Serializer\Serializer;

$apiKeyEnvVar = 'GEMINI_API_KEY';

if (!$geminiApiKey = getenv($apiKeyEnvVar)) {
    printf("Set the %s environment variable to continue.\n", $apiKeyEnvVar);
    exit(1);
}

// Create a suitable Symfony Serializer
$typeExtractor = new PropertyInfoExtractor([], [
    new ConstructorExtractor([new PhpDocExtractor()]),
]);

$serializer = new Serializer([
    new BackedEnumNormalizer(),
    new DateTimeNormalizer(),
    new ArrayDenormalizer(),
    new UnwrappingDenormalizer(),
    new ObjectNormalizer(propertyTypeExtractor: $typeExtractor),
]);

$httpClient = HttpClient::create([
    'timeout' => 120.0,
]);

// Create a client that will be used to compile and send queries
$geminiQueryClient = new QueryClient($serializer, $httpClient, $geminiApiKey);

// First, compile the query: this isn't strictly necessary, but by doing so,
// you can log or otherwise modify the request that will be sent to the LLM.
$compileRequest = new CompileRequest('gemini-2.5-flash')->withText(...[
    'text' => 'Who invented the PHP programming language?',
]);

$response = $geminiQueryClient->compile($compileRequest);

// Now that the query is compiled,
// create a request to execute the query
$executeRequest = new ExecuteRequest()
    ->forModel($response->getModel())
    ->withUrl($response->getUrl())
    ->withRequest($response->getRequest());

// Note: The `CompileResponse` object returned from
// the `compile()` call has a handy method which does
// the same work above but as a single method call:
//
//     $executeRequest = $response->toExecuteRequest();

// Finally, execute the query with the model
$response = $geminiQueryClient->execute($executeRequest);

printf("The model '%s' generated the following output in %dms:\n\n%s\n", $response->getModel(), $response->getRuntime(), $response->getOutput());
