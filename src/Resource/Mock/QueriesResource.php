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
        $requestContent = [
            'model' => $request->getModel()->getId(),
        ];

        // File Inputs
        foreach ($request->getFiles() as $file) {
            $requestContent['files'][] = [
                ...$file->toArray(),
            ];
        }

        // Prompt Inputs
        foreach ($request->getPrompts() as $prompt) {
            $requestContent['prompts'][] = [
                ...$prompt->toArray(),
            ];
        }

        // Instructions Input
        if ($prompt = $request->getInstructions()) {
            $requestContent['instructions'] = [
                ...$prompt->toArray(),
            ];
        }

        // Schema Input
        if ($schema = $request->getSchema()) {
            $requestContent['schema'] = [
                ...$schema->toArray(),
            ];
        }

        // Dimensions Input
        if ($dimensions = $request->getDimensions()) {
            $requestContent = array_merge($requestContent, [
                'dimensions' => $dimensions->getDimensions(),
            ]);
        }

        return new CompileQueryResponse($request->getModel(), $this->buildUrl($request->getModel()->isEmbedding() ? 'embeddings' : 'outputs'), $requestContent);
    }
}
