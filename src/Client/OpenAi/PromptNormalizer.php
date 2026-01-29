<?php

namespace OneToMany\AI\Client\OpenAi;

use OneToMany\AI\Contract\Client\PromptNormalizerInterface;
use OneToMany\AI\Contract\Request\Prompt\CompilePromptRequestInterface;
use OneToMany\AI\Request\Prompt\Content\CachedFile;
use OneToMany\AI\Request\Prompt\Content\InputText;
use OneToMany\AI\Request\Prompt\Content\JsonSchema;

use function str_starts_with;

final readonly class PromptNormalizer implements PromptNormalizerInterface
{
    /**
     * @see OneToMany\AI\Contract\Client\PromptNormalizerInterface
     *
     * @return array<non-empty-string, mixed>
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): array
    {
        $requestContent = [
            'model' => $data->model,
        ];

        foreach ($data->contents as $content) {
            if ($content instanceof InputText) {
                $requestContent['input'][] = [
                    'content' => [
                        [
                            'type' => 'input_text',
                            'text' => $content->getText(),
                        ],
                    ],
                    'role' => $content->getRole()->getValue(),
                ];
            }

            if ($content instanceof CachedFile) {
                $requestContent['input'][] = [
                    'content' => [
                        [
                            'type' => 'input_file',
                            'file_id' => $content->getUri(),
                        ],
                    ],
                    'role' => $content->getRole()->getValue(),
                ];
            }

            if ($content instanceof JsonSchema) {
                $requestContent['text'] = [
                    'format' => [
                        'type' => 'json_schema',
                        'name' => $content->getName(),
                        'schema' => $content->getSchema(),
                        'strict' => $content->isStrict(),
                    ],
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
        return $data instanceof CompilePromptRequestInterface && str_starts_with($data->getModel(), 'gpt');
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
