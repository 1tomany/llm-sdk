<?php

namespace OneToMany\AI\Contract\Action\Request;

use OneToMany\AI\Contract\Request\Prompt\DispatchPromptRequestInterface;
use OneToMany\AI\Contract\Response\Prompt\DispatchedPromptResponseInterface;

interface DispatchPromptActionInterface
{
    public function act(DispatchPromptRequestInterface $request): DispatchedPromptResponseInterface;
}
