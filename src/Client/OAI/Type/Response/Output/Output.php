<?php

namespace OneToMany\LlmSdk\Client\OpenAI\Type\Response\Output;

use OneToMany\LlmSdk\Client\OpenAI\Type\Response\Enum\Role;
use OneToMany\LlmSdk\Client\OpenAI\Type\Response\Enum\Status;
use OneToMany\LlmSdk\Client\OpenAI\Type\Response\Output\Content\OutputText;
use OneToMany\LlmSdk\Client\OpenAI\Type\Response\Output\Enum\Type;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;

use function array_map;
use function implode;
use function sprintf;
use function trim;

final readonly class Output
{
    /**
     * @param non-empty-string $id
     * @param ?list<OutputText> $content
     */
    public function __construct(
        public string $id,
        public Type $type,
        public ?Status $status = null,
        public ?array $content = null,
        public ?Role $role = null,
    ) {
        if ($type->isMessage() && empty($content)) {
            throw new InvalidArgumentException(sprintf('The content must be a non-empty-list when the type is "%s".', Type::Message->getValue()));
        }
    }

    /**
     * @return ?non-empty-string
     */
    public function getOutput(): ?string
    {
        if (!$this->content) {
            return null;
        }

        if ($this->type->isMessage() && $this->status?->isCompleted()) {
            $output = array_map(fn ($c) => (string) $c->text, $this->content);
        }

        return trim(implode('', $output ?? [])) ?: null;
    }
}
