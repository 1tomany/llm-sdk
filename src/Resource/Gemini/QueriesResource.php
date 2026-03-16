<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use OneToMany\LlmSdk\Request\Query\Type\FileUri;
use OneToMany\LlmSdk\Response\Query\CompileQueryResponse;

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

        // Text Inputs
        foreach ($request->getPrompts() as $text) {
            $requestContent[$contentKey]['parts'][] = [
                'text' => $text->getText(),
            ];
        }

        // Instructions Input
        if ($text = $request->getInstructions()) {
            $requestContent['systemInstruction'] = [
                'parts' => [
                    [
                        'text' => $text->getText(),
                    ],
                ],
            ];
        }

        // Dimensions Input
        if ($dimensions = $request->getDimensions()?->getDimensions()) {
            $requestContent['outputDimensionality'] = $dimensions;
        }

        // Schema Input
        if ($schema = $request->getSchema()) {
            $requestContent['generationConfig'] = [
                'responseMimeType' => $schema->getFormat(),
                'responseJsonSchema' => $schema->getSchema(),
            ];
        }

        return new CompileQueryResponse($request->getModel(), $requestContent);
    }
}
