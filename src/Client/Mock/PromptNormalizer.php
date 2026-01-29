<?php

namespace OneToMany\AI\Client\Mock;

use OneToMany\AI\Contract\Client\PromptNormalizerInterface;
use OneToMany\AI\Contract\Request\Prompt\CompilePromptRequestInterface;
use OneToMany\AI\Request\Prompt\Content\CachedFile;
use OneToMany\AI\Request\Prompt\Content\InputText;
use OneToMany\AI\Request\Prompt\Content\JsonSchema;

use function in_array;

/**
 * @see OneToMany\AI\Contract\Client\PromptNormalizerInterface
 *
 * @phpstan-type MockPromptFileUri array{
 *   fileUri: non-empty-string,
 *   format: non-empty-lowercase-string,
 * }
 * @phpstan-type MockPromptJsonSchema array{
 *   name: non-empty-string,
 *   schema: array<string, mixed>,
 *   format: non-empty-lowercase-string,
 * }
 * @phpstan-type MockPromptInputText array{
 *   text: non-empty-string,
 *   role: non-empty-string,
 * }
 */
final readonly class PromptNormalizer implements PromptNormalizerInterface
{
    public function __construct()
    {
    }

    /**
     * @see OneToMany\AI\Contract\Client\PromptNormalizerInterface
     *
     * @return array{contents: list<MockPromptInputText|MockPromptFileUri|MockPromptJsonSchema>}
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): array
    {
        $requestContent = ['contents' => []];

        foreach ($data->getContents() as $content) {
            if ($content instanceof InputText) {
                $requestContent['contents'][] = [
                    'text' => $content->getText(),
                    'role' => $content->getRole()->getValue(),
                ];
            }

            if ($content instanceof CachedFile) {
                $requestContent['contents'][] = [
                    'fileUri' => $content->getUri(),
                    'format' => $content->getFormat(),
                ];
            }

            if ($content instanceof JsonSchema) {
                $requestContent['schema'] = [
                    'name' => $content->getName(),
                    'schema' => $content->getSchema(),
                    'format' => $content->getFormat(),
                ];
            }
        }

        return $requestContent;
    }

    /**
     * @see OneToMany\AI\Contract\Client\PromptNormalizerInterface
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof CompilePromptRequestInterface && in_array($data->getVendor(), ['mock']);
    }

    /**
     * @see OneToMany\AI\Contract\Client\PromptNormalizerInterface
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            CompilePromptRequestInterface::class => true,
        ];
    }
}
