<?php

use OneToMany\LlmSdk\Client\Anthropic\AnthropicClient;
use OneToMany\LlmSdk\Client\Gemini\GeminiClient;
use OneToMany\LlmSdk\Client\Mock\MockClient;
use OneToMany\LlmSdk\Client\OpenAi\OpenAiClient;
use OneToMany\LlmSdk\Factory\ClientFactory;
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

require_once dirname(__DIR__).'/vendor/autoload.php';

// Create the Symfony Serializer component
$typeExtractor = new PropertyInfoExtractor([], [
    new ConstructorExtractor([new PhpDocExtractor()]),
]);

$objectNormalizer = new ObjectNormalizer(...[
    'propertyTypeExtractor' => $typeExtractor,
]);

$serializer = new Serializer([
    new BackedEnumNormalizer(),
    new DateTimeNormalizer(),
    new ArrayDenormalizer(),
    new UnwrappingDenormalizer(),
    $objectNormalizer,
]);

// Create the Symfony HTTP Client
$httpClient = HttpClient::create([
    'timeout' => 60.0,
]);

function successMessage(string $message, string ...$values): void
{
    printf("%s\n", vsprintf($message, $values));
}

function errorMessage(string $message, string ...$values): never
{
    printf("[ERROR] %s\n", vsprintf($message, $values));
    exit(1);
}

// Create each client that has an API key
$clientFactory = new ClientFactory([
    new MockClient(),
]);

if (is_string($apiKey = getenv('ANTHROPIC_API_KEY'))) {
    $clientFactory->addClient(new AnthropicClient($serializer, $httpClient, $apiKey));
}

if (is_string($apiKey = getenv('GEMINI_API_KEY'))) {
    $clientFactory->addClient(new GeminiClient($serializer, $httpClient, $apiKey));
}

if (is_string($apiKey = getenv('OPENAI_API_KEY'))) {
    $clientFactory->addClient(new OpenAiClient($serializer, $httpClient, $apiKey));
}

return $clientFactory;
