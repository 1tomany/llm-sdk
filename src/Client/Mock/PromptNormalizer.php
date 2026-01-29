<?php

namespace OneToMany\AI\Client\Mock;

use OneToMany\AI\Contract\Client\PromptNormalizerInterface;
use OneToMany\AI\Request\Prompt\Content\CachedFile;
use OneToMany\AI\Request\Prompt\Content\InputText;
use OneToMany\AI\Request\Prompt\Content\JsonSchema;

use function in_array;

/**
 * @phpstan-type TypeMockPromptFileUri array{
 *   fileUri: non-empty-string,
 * }
 * @phpstan-type TypeMockPromptSchema array{
 *   name: non-empty-string,
 *   schema: array<string, mixed>,
 * }
 * @phpstan-type TypeMockPromptText array{
 *   role: non-empty-string,
 *   text: non-empty-string,
 * }
 */
final readonly class PromptNormalizer implements PromptNormalizerInterface
{
    public function __construct()
    {
    }

    /**
     * @return array{contents: list<TypeMockPromptText|TypeMockPromptFileUri|TypeMockPromptSchema>}
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): array
    {
        $requestContent = ['contents' => []];

        foreach ($data->contents as $content) {
            if ($content instanceof InputText) {
                $requestContent['contents'][] = [
                    'text' => $content->getText(),
                    'role' => $content->getRole()->getValue(),
                ];
            }

            if ($content instanceof CachedFile) {
                $requestContent['contents'][] = [
                    'fileUri' => $content->getUri(),
                ];
            }

            if ($content instanceof JsonSchema) {
                $requestContent['schema'] = [
                    'name' => $content->name,
                    'schema' => $content->schema,
                ];
            }
        }

        return $requestContent;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof CompilePromptRequestInterface && in_array($data->vendor, ['mock']);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            CompilePromptRequestInterface::class => true,
        ];
    }
}
