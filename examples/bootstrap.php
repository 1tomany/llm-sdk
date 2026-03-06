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
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Serializer\Serializer;

require_once dirname(__DIR__).'/vendor/autoload.php';
require_once __DIR__.'/functions.php';

// Create the Symfony Serializer component
$typeExtractor = new PropertyInfoExtractor([], [
    new ConstructorExtractor([
        new PhpDocExtractor(),
    ]),
]);

$normalizers = [
    new BackedEnumNormalizer(),
    new DateTimeNormalizer(),
    new ArrayDenormalizer(),
    new UnwrappingDenormalizer(),
    new ObjectNormalizer(
        null,
        null,
        null,
        $typeExtractor,
    ),
];

$serializer = new Serializer($normalizers, [
    new JsonEncoder(),
    new XmlEncoder(),
]);

// Create the Symfony HTTP Client
$httpClient = HttpClient::create([
    'timeout' => 60.0,
]);

// Create each client that has an API key
$clientFactory = new ClientFactory([
    new MockClient(),
]);

// Anthropic Client
if (!empty($apiKey = getenv('ANTHROPIC_API_KEY'))) {
    $clientFactory->addClient(new AnthropicClient($httpClient, $serializer, $apiKey));
}

if (is_string($apiKey = getenv('GEMINI_API_KEY'))) {
    $clientFactory->addClient(new GeminiClient($httpClient, $serializer, $apiKey, 'v1beta'));
}

if (is_string($apiKey = getenv('OPENAI_API_KEY'))) {
    $clientFactory->addClient(new OpenAiClient($httpClient, $serializer, $apiKey, 'v1'));
}

return $clientFactory;
