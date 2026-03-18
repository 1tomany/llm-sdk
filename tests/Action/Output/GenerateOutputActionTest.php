<?php

namespace OneToMany\LlmSdk\Tests\Action\Output;

use OneToMany\LlmSdk\Action\Output\GenerateOutputAction;
use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Factory\ClientContainer;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ActionTests')]
#[Group('OutputTests')]
final class GenerateOutputActionTest extends TestCase
{
    public function testGeneratingOutputRequiresGenerativeModel(): void
    {
        $model = Model::MockEmbedding;
        $this->assertFalse($model->isGenerative());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The model "'.$model->getValue().'" is not a generative model.');

        new GenerateOutputAction(new ClientFactory())->act(new CompileQueryRequest($model));
    }
}
