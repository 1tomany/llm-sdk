<?php

namespace OneToMany\LlmSdk\Resource\OpenAi\Type\Response\Output\Content;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Response\Output\Content\Enum\Type;

use function sprintf;

final readonly class OutputText
{
    /**
     * @param ?non-empty-string $text
     * @param ?non-empty-string $refusal
     *
     * @throws InvalidArgumentException when the text and refusal are empty
     * @throws InvalidArgumentException when the type is output_text and the text is empty
     * @throws InvalidArgumentException when the type is refusal and the refusal is empty
     */
    public function __construct(
        public Type $type,
        public ?string $text = null,
        public ?string $refusal = null,
    ) {
        if (!$text && !$refusal) {
            throw new InvalidArgumentException('Both the text and refusal cannot be empty.');
        }

        if ($type->isOutputText() && !$text) {
            throw new InvalidArgumentException(sprintf('The text cannot be empty when the type is "%s".', Type::OutputText->getValue()));
        }

        if ($type->isRefusal() && !$refusal) {
            throw new InvalidArgumentException(sprintf('The refusal cannot be empty when the type is "%s".', Type::Refusal->getValue()));
        }
    }
}
