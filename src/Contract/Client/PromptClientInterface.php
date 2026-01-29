<?php

namespace OneToMany\AI\Contract\Client;

use OneToMany\AI\Contract\Request\Prompt\DispatchPromptRequestInterface;
use OneToMany\AI\Contract\Response\Prompt\DispatchedPromptResponseInterface;

interface PromptClientInterface
{
    public function send(DispatchPromptRequestInterface $request): DispatchedPromptResponseInterface;
}
