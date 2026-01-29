<?php

namespace OneToMany\AI\Client\OpenAi;

use OneToMany\AI\Contract\Client\PromptNormalizerInterface;
use OneToMany\AI\Contract\Request\Prompt\CompilePromptRequestInterface;
use OneToMany\AI\Request\Prompt\Content\CachedFile;
use OneToMany\AI\Request\Prompt\Content\InputText;
use OneToMany\AI\Request\Prompt\Content\JsonSchema;

use function in_array;

/**
 * @phpstan-type OpenAiPromptFileUri array{
 *   type: 'input_file',
 *   file_id: non-empty-string,
 * }
 * @phpstan-type OpenAiPromptInputText array{
 *   type: 'input_text',
 *   text: non-empty-string,
 * }
 */
final readonly class PromptNormalizer implements PromptNormalizerInterface
{
    /**
     * @see OneToMany\AI\Contract\Client\PromptNormalizerInterface
     *
     * @return array{
     *   input?: non-empty-list<
     *     array{
     *       content: non-empty-list<OpenAiPromptFileUri|OpenAiPromptInputText>,
     *       role: 'system'|'user',
     *     },
     *   >,
     *   text?: array{
     *     format: array{
     *       type: 'json_schema',
     *       name: non-empty-lowercase-string,
     *       schema: array<string, mixed>,
     *       strict: bool,
     *     },
     *   },
     * }
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): array
    {
        $requestContent = [
            'model' => $data->getModel(),
        ];

        foreach ($data->getContents() as $content) {
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
        return $data instanceof CompilePromptRequestInterface && in_array($data->getVendor(), ['openai']);
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
