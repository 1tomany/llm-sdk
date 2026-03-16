<?php

namespace OneToMany\LlmSdk\Tests\Request\Output;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Output\GenerateOutputRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('RequestTests')]
#[Group('OutputTests')]
final class GenerateOutputRequestTest extends TestCase
{
    public function testConstructorRequiresGenerativeModel(): void
    {
        $model = Model::MockEmbedding;
        $this->assertFalse($model->isGenerative());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The model "'.$model->getValue().'" is not a generative model.');

        new GenerateOutputRequest($model, 'https://mock-llm.service', []);
    }
}
