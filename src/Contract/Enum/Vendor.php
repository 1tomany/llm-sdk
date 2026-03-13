<?php

namespace OneToMany\LlmSdk\Contract\Enum;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;

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
     * @throws InvalidArgumentException when the vendor does not exist
     */
    public static function create(string|self|null $vendor): self
    {
        if ($vendor instanceof self) {
            return $vendor;
        }

        $vendor = trim($vendor ?? '');

        try {
            return self::from(strtolower($vendor));
        } catch (\TypeError|\ValueError $e) {
            throw new InvalidArgumentException(sprintf('The vendor "%s" does not exist.', $vendor), previous: $e);
        }
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
        return \array_values(\array_filter(Model::cases(), fn ($m): bool => $m->usesVendor($this)));
    }
}
