<?php

namespace OneToMany\LlmSdk\Client\OpenAi;

use OneToMany\LlmSdk\Contract\Client\FileClientInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class OpenAiClient
{
    private ?FileClientInterface $fileClient = null;

    /**
     * @param non-empty-string $apiKey
     */
    public function __construct(
        private DenormalizerInterface $denormalizer,
        private HttpClientInterface $httpClient,
        #[\SensitiveParameter] private string $apiKey,
    ) {
    }

    public function files(): FileClientInterface
    {
        if (null === $this->fileClient) {
            $this->fileClient = new FileClient($this->denormalizer, $this->httpClient, $this->apiKey);
        }

        return $this->fileClient;
    }
}
