<?php

namespace OneToMany\LlmSdk\Resource\Mock;

use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use OneToMany\LlmSdk\Resource\Mock\Trait\GenerateIdTrait;
use OneToMany\LlmSdk\Response\Query\CompileQueryResponse;

use function array_merge;

final readonly class QueriesResource implements QueriesResourceInterface
{
    use GenerateIdTrait;

    public function __construct()
    {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
     */
    public function compile(CompileQueryRequest $request): CompileQueryResponse
    {
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
        foreach ($request->getFileInputs() as $file) {
            $requestContent['fileInputs'][] = [
                ...$file->toArray(),
            ];
        }

        // Text Inputs
        foreach ($request->getTextInputs() as $text) {
            $requestContent['textInputs'][] = [
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

        return new CompileQueryResponse($request->getModel(), $requestContent);
    }
}
