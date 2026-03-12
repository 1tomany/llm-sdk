<?php

namespace OneToMany\LlmSdk\Factory\Exception;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use Psr\Container\NotFoundExceptionInterface;

class ContainerEntryNotFoundException extends InvalidArgumentException implements NotFoundExceptionInterface
{
}
