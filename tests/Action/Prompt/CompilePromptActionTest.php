<?php

namespace OneToMany\AI\Tests\Action\Prompt;

use OneToMany\AI\Action\Prompt\CompilePromptAction;
use OneToMany\AI\Exception\InvalidArgumentException;
use OneToMany\AI\Factory\PromptClientFactory;
use OneToMany\AI\Request\Prompt\CompilePromptRequest;
use OneToMany\AI\Tests\Factory\ClientContainer;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ActionTests')]
#[Group('PromptTests')]
final class CompilePromptActionTest extends TestCase
{
    public function testCompilingPromptRequiresNonEmptyContents(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Compiling the prompt for the model "mock" failed because the contents are empty.');

        new CompilePromptAction(new PromptClientFactory(new ClientContainer()))->act(new CompilePromptRequest('mock', 'mock', []));
    }
}
