<?php

namespace OneToMany\AI\Tests\Action\Prompt;

use OneToMany\AI\Action\Prompt\CompilePromptAction;
use OneToMany\AI\Exception\InvalidArgumentException;
use OneToMany\AI\Request\Prompt\CompilePromptRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class CompilePromptActionTest extends TestCase
{
    public function testCompilingPromptRequiresNonEmptyContents(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Compiling the prompt for the model "mock" failed because the contents are empty.');

        new CompilePromptAction(new ObjectNormalizer())->act(new CompilePromptRequest('mock', 'mock', []));
    }
}
