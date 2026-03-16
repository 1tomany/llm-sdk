<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Resource\Gemini\Type\Embedding\Embedding;
use OneToMany\LlmSdk\Response\Query\CompileResponse;

use function array_merge;

final readonly class QueriesResource extends BaseResource implements QueriesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
     */
    public function compile(CompileRequest $request): CompileResponse
    {
        $contentKey = $request->getModel()->isEmbedding() ? 'content' : 'contents';

        $requestContent = [
            $contentKey => [
                'parts' => [],
            ],
        ];

        /*
        // File Prompt Components
        foreach ($request->getFileInputs() as $file) {
            $requestContent[$contentKey]['parts'][] = [
                'fileData' => [
                    'fileUri' => $file->getUri(),
                    'mimeType' => $file->getFormat(),
                ],
            ];
        }

        // Text Prompt Components
        foreach ($request->getPrompts() as $prompt) {
            $requestContent[$contentKey]['parts'][] = [
                'text' => $prompt->getPrompt(),
            ];
        }

        // Instructions Prompt Component
        if ($prompt = $request->getInstructions()) {
            $requestContent['systemInstruction'] = [
                'parts' => [
                    [
                        'text' => $prompt->getPrompt(),
                    ],
                ],
            ];
        }
        */

        // Embedding Dimensions Component
        if ($dimensionality = $request->getDimensions()) {
            $requestContent = array_merge($requestContent, [
                'outputDimensionality' => $dimensionality,
            ]);
        }

        // Schema Prompt Component
        if ($schema = $request->getSchema()) {
            $requestContent['generationConfig'] = [
                'responseMimeType' => $schema->getFormat(),
                'responseJsonSchema' => $schema->getSchema(),
            ];
        }

        return new CompileResponse($request->getModel(), $requestContent);
    }
}
