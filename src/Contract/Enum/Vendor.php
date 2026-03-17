<?php

namespace OneToMany\LlmSdk\Contract\Enum;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;

use function array_filter;
use function array_values;
use function is_object;
use function sprintf;
use function strtolower;
use function trim;

enum Vendor: string
{
    case Anthropic = 'anthropic';
    case Gemini = 'gemini';
    case Mock = 'mock';
    case OpenAI = 'openai';

    /**
     * @throws InvalidArgumentException when the vendor name is empty
     * @throws InvalidArgumentException when the vendor is not valid
     */
    public static function create(string|self|null $vendor): self
    {
        if (is_object($vendor)) {
            return $vendor;
        }

        if (!$vendor = trim($vendor ?? '')) {
            throw new InvalidArgumentException('The vendor name cannot be empty.');
        }

        return self::tryFrom(strtolower($vendor)) ?? throw new InvalidArgumentException(sprintf('The vendor "%s" is not valid.', $vendor));
    }

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return non-empty-lowercase-string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return list<Model>
     */
    public function getModels(): array
    {
        return array_values(array_filter(Model::cases(), fn (Model $m): bool => $m->usesVendor($this)));
    }
}
