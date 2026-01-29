<?php

namespace OneToMany\AI\Contract\Action\Prompt;

use OneToMany\AI\Contract\Request\Prompt\CompilePromptRequestInterface;
use OneToMany\AI\Contract\Response\Prompt\CompiledPromptResponseInterface;

interface CompilePromptActionInterface
{
    public function act(CompilePromptRequestInterface $request): CompiledPromptResponseInterface;
}
