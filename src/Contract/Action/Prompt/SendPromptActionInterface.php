<?php

namespace OneToMany\AI\Contract\Action\Prompt;

use OneToMany\AI\Contract\Request\Prompt\SendPromptRequestInterface;
use OneToMany\AI\Contract\Response\Prompt\SentPromptResponseInterface;

interface SendPromptActionInterface
{
    public function act(SendPromptRequestInterface $request): SentPromptResponseInterface;
}
