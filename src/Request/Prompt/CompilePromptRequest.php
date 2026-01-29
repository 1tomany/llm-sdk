<?php

namespace OneToMany\AI\Request\Prompt;

use OneToMany\AI\Contract\Request\Prompt\CompilePromptRequestInterface;
use OneToMany\AI\Contract\Request\Prompt\Content\ContentInterface;

final class CompilePromptRequest implements CompilePromptRequestInterface
{
    /**
     * @param non-empty-lowercase-string $vendor
     * @param non-empty-lowercase-string $model
     * @param list<ContentInterface> $contents
     */
    public function __construct(
        private readonly string $vendor,
        private readonly string $model,
        private array $contents = [],
    ) {
    }

    /**
     * @see OneToMany\AI\Contract\Request\Prompt\CompilePromptRequestInterface
     */
    public function getVendor(): string
    {
        return $this->vendor;
    }

    /**
     * @see OneToMany\AI\Contract\Request\Prompt\CompilePromptRequestInterface
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @see OneToMany\AI\Contract\Request\Prompt\CompilePromptRequestInterface
     */
    public function getContents(): array
    {
        return $this->contents;
    }

    /**
     * @see OneToMany\AI\Contract\Request\Prompt\CompilePromptRequestInterface
     */
    public function addContent(ContentInterface $content): static
    {
        $this->contents[] = $content;

        return $this;
    }
}
