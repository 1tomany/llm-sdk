<?php

namespace OneToMany\AI\Tests\Client\Gemini;

use OneToMany\AI\Client\Gemini\BaseClient;
use OneToMany\AI\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class BaseClientTest extends TestCase
{
    public function testConstructingClientRequiresGeminiApiKeyOrScopedHttpClient(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Constructing a Gemini client requires either an API key or scoped HTTP client, but neither were provided.');

        $this->getMockBuilder(BaseClient::class)->setConstructorArgs([null, null, new ObjectNormalizer()])->getMock();
    }
}
