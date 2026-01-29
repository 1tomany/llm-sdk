<?php

namespace App\Prompt\Vendor\Model\Client\Gemini;

use App\Prompt\Vendor\Model\Contract\Client\PromptNormalizerInterface;
use App\Prompt\Vendor\Model\Contract\Request\Prompt\CompilePromptRequestInterface;
use App\Prompt\Vendor\Model\Prompt\PromptContentFile;
use App\Prompt\Vendor\Model\Prompt\PromptContentSchema;
use App\Prompt\Vendor\Model\Prompt\PromptContentText;

use function in_array;

/**
 * @phpstan-type TypeGeminiPromptFileUri array{
 *   fileData: array{
 *     fileUri: non-empty-string,
 *   },
 * }
 * @phpstan-type TypeGeminiPromptText array{
 *   text: non-empty-string,
 * }
 */
final readonly class PromptNormalizer implements PromptNormalizerInterface
{
    /**
     * @see App\Prompt\Vendor\Model\Contract\Client\PromptNormalizerInterface
     *
     * @return array{
     *   systemInstruction?: array{
     *     parts: non-empty-list<TypeGeminiPromptText>,
     *     role: 'system',
     *   },
     *   contents: list<
     *     array{
     *       parts: non-empty-list<TypeGeminiPromptText|TypeGeminiPromptFileUri>,
     *       role: 'user',
     *     },
     *   >,
     *   generationConfig?: array{
     *     responseMimeType: non-empty-lowercase-string,
     *     responseJsonSchema: array<string, mixed>,
     *   },
     * }
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): array
    {
        $requestContent = ['contents' => []];

        foreach ($data->contents as $content) {
            if ($content instanceof PromptContentText) {
                if ($content->role->isSystem()) {
                    $requestContent['systemInstruction'] = [
                        'parts' => [
                            [
                                'text' => $content->text,
                            ],
                        ],
                        'role' => 'system',
                    ];
                }

                if ($content->role->isUser()) {
                    $requestContent['contents'][] = [
                        'parts' => [
                            [
                                'text' => $content->text,
                            ],
                        ],
                        'role' => 'user',
                    ];
                }
            }

            if ($content instanceof PromptContentFile) {
                $requestContent['contents'][] = [
                    'parts' => [
                        [
                            'fileData' => [
                                'fileUri' => $content->uri,
                            ],
                        ],
                    ],
                    'role' => 'user',
                ];
            }

            if ($content instanceof PromptContentSchema) {
                $requestContent['generationConfig'] = [
                    'responseMimeType' => $content->format,
                    'responseJsonSchema' => $content->schema,
                ];
            }
        }

        return $requestContent;
    }

    /**
     * @see App\Prompt\Vendor\Model\Contract\Client\PromptNormalizerInterface
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof CompilePromptRequestInterface && in_array($data->vendor, ['gemini']);
    }

    /**
     * @see App\Prompt\Vendor\Model\Contract\Client\PromptNormalizerInterface
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            CompilePromptRequestInterface::class => true,
        ];
    }
}
