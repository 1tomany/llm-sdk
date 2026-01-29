<?php

namespace OneToMany\AI\Tests\Client\Gemini;

use OneToMany\AI\Client\Gemini\GeminiClient;
use OneToMany\AI\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;

final class GeminiClientTest extends TestCase
{
    public function testConstructingClientRequiresGeminiApiKeyOrScopedHttpClient(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Constructing a Gemini client requires either an API key or scoped HTTP client, but neither were provided.');

        $this->getMockBuilder(GeminiClient::class)->setConstructorArgs([null, null, new ArrayDenormalizer()])->getMock();
    }
}
