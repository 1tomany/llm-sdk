<?php

namespace OneToMany\LlmSdk\Exception;

use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface;
use OneToMany\LlmSdk\Exception\Trait\JsonSerializeTrait;

class RuntimeException extends \RuntimeException implements ExceptionInterface
{
    use JsonSerializeTrait;
}
