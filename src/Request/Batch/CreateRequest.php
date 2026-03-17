<?php

namespace OneToMany\LlmSdk\Request\Batch;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Request\BaseRequest;

use function trim;

class CreateRequest extends BaseRequest
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        string|Model|null $model,
        private readonly string $name,
    )
    {
        parent::__construct($model);
    }

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
