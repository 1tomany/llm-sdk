<?php

namespace OneToMany\LlmSdk\Client\Gemini;

use OneToMany\LlmSdk\Contract\Client\BatchClientInterface;
use OneToMany\LlmSdk\Contract\Client\FileClientInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class GeminiClient
{
    private ?BatchClientInterface $batchClient = null;
    private ?FileClientInterface $fileClient = null;

    // public const string BASE_URI = 'https://generativelanguage.googleapis.com';

    /**
     * @param non-empty-string $apiKey
     * @param non-empty-string $apiVersion
     */
    public function __construct(
        private readonly DenormalizerInterface $denormalizer,
        private readonly HttpClientInterface $httpClient,
        #[\SensitiveParameter] private string $apiKey,
        private readonly string $apiVersion = 'v1beta',
    ) {
    }

    public function batches(): BatchClientInterface
    {
        if (null === $this->batchClient) {
            $this->batchClient = new BatchClient($this->denormalizer, $this->httpClient, $this->apiKey, $this->apiVersion);
        }

        return $this->batchClient;
    }

    public function files(): FileClientInterface
    {
        if (null === $this->fileClient) {
            $this->fileClient = new FileClient($this->denormalizer, $this->httpClient, $this->apiKey, $this->apiVersion);
        }

        return $this->fileClient;
    }
}
