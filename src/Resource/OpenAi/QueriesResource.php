<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Response\Query\CompileResponse;

final readonly class QueriesResource extends BaseResource implements QueriesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
     */
    public function compile(CompileRequest $request): CompileResponse
    {
        // $url = $this->buildUrl($request->getModel()->isEmbedding() ? 'embeddings' : 'responses');

        $requestContent = [
            'model' => $request->getModel()->getId(),
        ];

        /*
        if ($request->getModel()->isEmbedding()) {
            // Text Prompt Components
            foreach ($request->getPrompts() as $prompt) {
                $requestContent['input'] = $prompt->getPrompt();
            }

            // Embedding Dimensions Component
            if ($dimensions = $request->getDimensions()) {
                $requestContent['dimensions'] = $dimensions;
            }
        } else {
            $requestContent['input'] = [];

            // File Prompt Components
            foreach ($request->getFileInputs() as $file) {
                $type = match ($file->isImage()) {
                    true => Type::Image,
                    false => Type::File,
                };

                $requestContent['input'][] = [
                    'content' => [
                        [
                            'type' => $type->getValue(),
                            'file_id' => $file->getUri(),
                        ],
                    ],
                    'role' => $file->getRole()->getValue(),
                ];
            }

            // Text Prompt Components
            foreach ($request->getPrompts() as $prompt) {
                $type = Type::Text;

                $requestContent['input'][] = [
                    'content' => [
                        [
                            'type' => $type->getValue(),
                            'text' => $prompt->getPrompt(),
                        ],
                    ],
                    'role' => $prompt->getRole()->getValue(),
                ];
            }

            // Schema Prompt Component
            if ($schema = $request->getSchema()) {
                $type = Type::Schema;

                $requestContent['text'] = [
                    'format' => [
                        'type' => $type->getValue(),
                        'name' => $schema->getName(),
                        'schema' => $schema->getSchema(),
                        'strict' => $schema->isStrict(),
                    ],
                ];
            }
        }
        */

        return new CompileResponse($request->getModel(), $requestContent);
    }
}
