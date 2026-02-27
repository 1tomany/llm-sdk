<?php

namespace OneToMany\LlmSdk\Client\Trait;

use OneToMany\LlmSdk\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;

trait DenormalizerTrait
{
    /**
     * @template T of object
     *
     * @param class-string<T> $type
     * @param array<string, mixed> $context
     *
     * @return T
     */
    protected function denormalize(mixed $content, string $type, array $context = []): object
    {
        try {
            $object = $this->denormalizer->denormalize($content, $type, null, $context);
        } catch (SerializerExceptionInterface $e) {
            throw new RuntimeException($e->getMessage(), previous: $e);
        }

        return $object;
    }
}
