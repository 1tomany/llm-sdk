<?php

namespace OneToMany\AI\Tests\Action\Prompt;

use ArrayObject;
use OneToMany\AI\Action\Prompt\CompilePromptAction;
use OneToMany\AI\Contract\Action\Prompt\CompilePromptActionInterface;
use OneToMany\AI\Exception\InvalidArgumentException;
use OneToMany\AI\Request\Prompt\CompilePromptRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class CompilePromptActionTest extends TestCase
{
    public function testCompilingPromptRequiresNonEmptyContents(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Compiling the prompt for the model "mock" failed because the contents are empty.');

        $this->createAction()->act(new CompilePromptRequest('mock', 'mock', []));
    }

    private function createAction(): CompilePromptActionInterface
    {
        $normalizer = new class implements NormalizerInterface
        {
            public function normalize(mixed $data, ?string $format = null, array $context = []): null
            {
                throw new \RuntimeException('Not implemented!');
            }

            public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
            {
                return false;
            }

            public function getSupportedTypes(?string $format): array
            {
                return [];
            }
        };

        return new CompilePromptAction($normalizer);
    }
}
