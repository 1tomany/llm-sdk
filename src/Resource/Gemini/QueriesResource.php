<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use OneToMany\LlmSdk\Request\Type\File\FileUri;
use OneToMany\LlmSdk\Request\Type\Query\Tool\SearchStore;
use OneToMany\LlmSdk\Response\Query\CompileQueryResponse;

use function sprintf;

final readonly class QueriesResource extends BaseResource implements QueriesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
     */
    public function compile(CompileQueryRequest $request): CompileQueryResponse
    {
        $contentKey = $request->getModel()->isEmbedding() ? 'content' : 'contents';

        $requestContent = [
            $contentKey => [
                'parts' => [],
            ],
        ];

        // File Inputs
        foreach ($request->getFiles() as $file) {
            if ($file instanceof FileUri) {
                $requestContent[$contentKey]['parts'][] = [
                    'fileData' => [
                        'fileUri' => $file->getUri(),
                        'mimeType' => $file->getFormat(),
                    ],
                ];
            }
        }

        // Prompt Inputs
        foreach ($request->getPrompts() as $prompt) {
            $requestContent[$contentKey]['parts'][] = [
                'text' => $prompt->getText(),
            ];
        }

        // Tool Inputs
        foreach ($request->getTools() as $tool) {
            if (!isset($requestContent['tools'])) {
                $requestContent['tools'] = [];
            }

            if ($tool instanceof SearchStore) {
                $requestContent['tools'][] = [
                    'fileSearch' => [
                        'fileSearchStoreNames' => [
                            $tool->getUri(),
                        ],
                    ],
                ];
            }
        }

        // Instructions Input
        if ($prompt = $request->getInstructions()) {
            $requestContent['systemInstruction'] = [
                'parts' => [
                    [
                        'text' => $prompt->getText(),
                    ],
                ],
            ];
        }

        // Schema Input
        if ($schema = $request->getSchema()) {
            $requestContent['generationConfig'] = [
                'responseMimeType' => $schema->getFormat(),
                'responseJsonSchema' => $schema->getSchema(),
            ];
        }

        // Dimensions Input
        if ($dimensions = $request->getDimensions()?->getDimensions()) {
            $requestContent['outputDimensionality'] = $dimensions;
        }

        return new CompileQueryResponse($request->getModel(), $this->buildUrl($this->getApiVersion(), sprintf('models/%s:%s', $request->getModel()->getId(), $request->getModel()->isEmbedding() ? 'embedContent' : 'generateContent')), $requestContent);
    }
}
