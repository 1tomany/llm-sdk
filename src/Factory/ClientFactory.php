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
    public function __construct(private ContainerInterface $container)
    {
    }

    /**
     * @throws InvalidArgumentException when the container does not have a client registered for the vendor
     */
    public function create(Vendor $vendor): ClientInterface
    {
        try {
            $client = $this->container->get($vendor->getValue());
        } catch (ContainerExceptionInterface $e) {
        }

        if (!isset($client) || !$client instanceof ClientInterface) {
            throw new InvalidArgumentException(sprintf('Creating a client failed because the container does not have one registered for the vendor "%s".', $vendor->getValue()), previous: $e ?? null);
        }

        return $client;
    }
}
