<?php

namespace OneToMany\LlmSdk\Client\Trait;

use function in_array;

trait SupportsModelTrait
{
    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     *
     * @param non-empty-lowercase-string $model
     */
    public function supportsModel(string $model): bool
    {
        return in_array($model, self::getModels());
    }
}
