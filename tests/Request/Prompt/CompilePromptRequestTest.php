<?php

namespace OneToMany\AI\Tests\Request\Prompt;

use OneToMany\AI\Request\Prompt\CompilePromptRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('RequestTests')]
#[Group('PromptTests')]
final class CompilePromptRequestTest extends TestCase
{
    public function testConstructingPromptWithNoContentsDoesNotThrowException(): void
    {
        $this->assertCount(0, new CompilePromptRequest('mock', 'mock', [])->getContents());
    }
}
