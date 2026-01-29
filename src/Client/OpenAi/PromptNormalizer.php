<?php

namespace App\Prompt\Vendor\Model\Client\OpenAi;

use App\Prompt\Vendor\Model\Contract\Client\PromptNormalizerInterface;
use App\Prompt\Vendor\Model\Contract\Request\Prompt\CompilePromptRequestInterface;
use App\Prompt\Vendor\Model\Prompt\PromptContentFile;
use App\Prompt\Vendor\Model\Prompt\PromptContentSchema;
use App\Prompt\Vendor\Model\Prompt\PromptContentText;

use function str_starts_with;

final readonly class PromptNormalizer implements PromptNormalizerInterface
{
    /**
     * @see App\Prompt\Vendor\Model\Contract\Client\PromptNormalizerInterface
     *
     * @return array<non-empty-string, mixed>
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): array
    {
        $requestContent = [
            'model' => $data->model,
        ];

        foreach ($data->contents as $content) {
            if ($content instanceof PromptContentText) {
                if ($content->role->isSystem()) {
                    $requestContent['input'][] = [
                        'content' => [
                            [
                                'type' => 'input_text',
                                'text' => $content->text,
                            ],
                        ],
                        'role' => 'system',
                    ];
                }

                if ($content->role->isUser()) {
                    $requestContent['input'][] = [
                        'content' => [
                            [
                                'type' => 'input_text',
                                'text' => $content->text,
                            ],
                        ],
                        'role' => 'user',
                    ];
                }
            }

            if ($content instanceof PromptContentFile) {
                $requestContent['input'][] = [
                    'content' => [
                        [
                            'type' => 'input_file',
                            'file_id' => $content->uri,
                        ],
                    ],
                    'role' => 'user',
                ];
            }

            if ($content instanceof PromptContentSchema) {
                $requestContent['text'] = [
                    'format' => [
                        'type' => 'json_schema',
                        'name' => $content->name,
                        'schema' => $content->schema,
                        'strict' => true,
                    ],
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
        return $data instanceof CompilePromptRequestInterface && str_starts_with($data->model, 'gpt');
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
