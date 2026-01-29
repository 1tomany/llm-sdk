<?php

namespace OneToMany\AI\Tests\Factory;

use OneToMany\AI\Contract\Client\FileClientInterface;
use OneToMany\AI\Contract\Client\PromptClientInterface;
use Psr\Container\ContainerInterface;

class ClientContainer implements ContainerInterface
{
    /**
     * @param array<non-empty-unique-lowercase-string, FileClientInterface|PromptClientInterface> $clients
     */
    public function __construct(private array $clients = [])
    {
    }

    /**
     * @param non-empty-lowercase-string $id
     */
    public function get(string $id): FileClientInterface|PromptClientInterface|null
    {
        return $this->clients[$id] ?? null;
    }

    /**
     * @param non-empty-lowercase-string $id
     */
    public function has(string $id): bool
    {
        return isset($this->clients[$id]);
    }
}
