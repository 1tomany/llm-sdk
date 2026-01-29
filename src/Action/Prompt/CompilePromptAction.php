<?php

namespace OneToMany\AI\Action\Prompt;

use OneToMany\AI\Contract\Action\Prompt\CompilePromptActionInterface;
use OneToMany\AI\Contract\Request\Prompt\CompilePromptRequestInterface;
use OneToMany\AI\Contract\Response\Prompt\CompiledPromptResponseInterface;
use OneToMany\AI\Exception\InvalidArgumentException;
use OneToMany\AI\Exception\RuntimeException;
use OneToMany\AI\Response\Prompt\CompiledPromptResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use function sprintf;

final readonly class CompilePromptAction implements CompilePromptActionInterface
{
    public function __construct(private NormalizerInterface $normalizer)
    {
    }

    /**
     * @see OneToMany\AI\Contract\Action\Prompt\CompilePromptActionInterface
     */
    public function act(CompilePromptRequestInterface $request): CompiledPromptResponseInterface
    {
        if (!$request->hasContents()) {
            throw new InvalidArgumentException(sprintf('Compiling the prompt for the model "%s" failed because the contents are empty.', $request->getModel()));
        }

        try {
            /** @var array<string, mixed> $compiledRequest */
            $compiledRequest = $this->normalizer->normalize($request);
        } catch (SerializerExceptionInterface $e) {
            throw new RuntimeException(sprintf('Compiling the prompt for the model "%s" failed.', $request->getModel()), previous: $e);
        }

        return new CompiledPromptResponse($request->getVendor(), $request->getModel(), $compiledRequest);
    }
}
