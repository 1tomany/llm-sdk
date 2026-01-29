<?php

namespace OneToMany\AI\Contract\Response\Prompt;

interface SentPromptResponseInterface
{
    /**
     * @return array<string, mixed>
     */
    public function __invoke(): array;

    /**
     * @return non-empty-lowercase-string
     */
    public function getVendor(): string;

    /**
     * @return non-empty-lowercase-string
     */
    public function getModel(): string;

    /**
     * @return non-empty-string
     */
    public function getUri();

    /**
     * @return ?non-empty-string
     */
    public function getOutput(): ?string;

    /**
     * @return array<string, mixed>
     */
    public function getResponse(): array;

    /**
     * @return non-negative-int|float
     */
    public function getRuntime(): int|float;
}
