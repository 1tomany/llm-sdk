<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use OneToMany\LlmSdk\Request\Query\Type\FileUri;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Response\Input\Enum\Type;
use OneToMany\LlmSdk\Response\Query\CompileQueryResponse;

use function array_merge;

final readonly class QueriesResource extends BaseResource implements QueriesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
     */
    public function compile(CompileQueryRequest $request): CompileQueryResponse
    {
        $requestContent = [
            'model' => $request->getModel()->getId(),
        ];

        if ($request->getModel()->isEmbedding()) {
            // Text Inputs
            foreach ($request->getPrompts() as $text) {
                $requestContent['input'] = $text->getText();
            }

            // Dimensions Input
            if ($dimensions = $request->getDimensions()) {
                $requestContent = array_merge($requestContent, [
                    'dimensions' => $dimensions->getDimensions(),
                ]);
            }
        } else {
            $requestContent['input'] = [];

            $fileTyper = function (FileUri $file): Type {
                return $file->isImage() ? Type::Image : Type::File;
            };

            // File Inputs
            foreach ($request->getFiles() as $file) {
                $type = $fileTyper($file);

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

            // Text Inputs
            $type = Type::Text;

            foreach ($request->getPrompts() as $text) {
                $requestContent['input'][] = [
                    'content' => [
                        [
                            'text' => $text->getText(),
                            'type' => $type->getValue(),
                        ],
                    ],
                    'role' => $text->getRole()->getValue(),
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

        return new CompileQueryResponse($request->getModel(), $requestContent);
    }
}
