<?php

namespace OneToMany\LlmSdk\Factory;

use OneToMany\LlmSdk\Contract\Client\ClientInterface;
use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

use function sprintf;

final readonly class ClientFactory
{
    public function __construct(
        private ContainerInterface $container = new ClientContainer(),
    ) {
    }

    /**
     * @template C of ClientInterface
     *
     * @param class-string<C> $clientType
     *
     * @return C
     *
     * @throws InvalidArgumentException when the client could not be found
     */
    public function create(Vendor $vendor, string $clientType): ClientInterface
    {
        try {
            $client = $this->container->get($vendor->getValue());
        } catch (ContainerExceptionInterface $e) {
        }

        if (!isset($client) || !$client instanceof $clientType) {
            throw new InvalidArgumentException(sprintf('%s client not found.', $vendor->getName()), previous: $e ?? null);
        }

        return $client;
    }
}
