<?php

namespace OneToMany\LlmSdk\Exception;

use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface;
use OneToMany\LlmSdk\Exception\Trait\JsonSerializeTrait;

class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{
    use JsonSerializeTrait;
}
