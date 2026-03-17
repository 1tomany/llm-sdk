<?php

namespace OneToMany\LlmSdk\Resource\Mock;

use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use OneToMany\LlmSdk\Response\Query\CompileQueryResponse;

use function array_merge;

final readonly class QueriesResource extends BaseResource implements QueriesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
     */
    public function compile(CompileQueryRequest $request): CompileQueryResponse
    {
        $url = $this->buildUrl($request->getModel()->isEmbedding() ? 'embeddings' : 'outputs');

        $requestContent = [
            'model' => $request->getModel()->getId(),
        ];

        // Dimensions Input
        if (null !== $dimensions = $request->getDimensions()) {
            $requestContent = array_merge($requestContent, [
                'dimensions' => $dimensions->getDimensions(),
            ]);
        }

        // File Inputs
        foreach ($request->getFiles() as $file) {
            $requestContent['files'][] = [
                ...$file->toArray(),
            ];
        }

        // Text Inputs
        foreach ($request->getPrompts() as $text) {
            $requestContent['prompts'][] = [
                ...$text->toArray(),
            ];
        }

        // System Instructions Input
        if (null !== $text = $request->getInstructions()) {
            $requestContent['instructions'] = $text->toArray();
        }

        // Schema Input
        if (null !== $schema = $request->getSchema()) {
            $requestContent['schema'] = $schema->toArray();
        }

        return new CompileQueryResponse($request->getModel(), $url, $requestContent);
    }
}
