<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use OneToMany\LlmSdk\Request\Type\File\FileUri;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Request\Response\Enum\Type;
use OneToMany\LlmSdk\Response\Query\CompileQueryResponse;

use function array_merge;

final readonly class QueriesResource extends BaseResource implements QueriesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
     *
     * @throws InvalidArgumentException when a query with no prompts for an embedding model is compiled
     */
    public function compile(CompileQueryRequest $request): CompileQueryResponse
    {
        $requestContent = [
            'model' => $request->getModel()->getId(),
        ];

        if ($request->getModel()->isEmbedding()) {
            if (!$request->hasPrompts()) {
                throw new InvalidArgumentException('At least one prompt is required.');
            }

            // Prompt Input
            $requestContent['input'] = $request->getPrompts()[0]->getText();

            // Dimensions Input
            if ($dimensions = $request->getDimensions()) {
                $requestContent = array_merge($requestContent, [
                    'dimensions' => $dimensions->getDimensions(),
                ]);
            }
        } else {
            $requestContent['input'] = [];

            // File Inputs
            $fileTypeResolver = function (FileUri $file): Type {
                return $file->isImage() ? Type::Image : Type::File;
            };

            foreach ($request->getFiles() as $file) {
                $type = $fileTypeResolver($file);

                if ($file instanceof FileUri) {
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
            }

            // Prompt Inputs
            $type = Type::Text;

            foreach ($request->getPrompts() as $prompt) {
                $requestContent['input'][] = [
                    'content' => [
                        [
                            'type' => $type->getValue(),
                            'text' => $prompt->getText(),
                        ],
                    ],
                    'role' => $prompt->getRole()->getValue(),
                ];
            }

            // Schema Input
            $type = Type::Schema;

            if ($schema = $request->getSchema()) {
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

        return new CompileQueryResponse($request->getModel(), $this->buildUrl($request->getModel()->isEmbedding() ? 'embeddings' : 'responses'), $requestContent);
    }
}
