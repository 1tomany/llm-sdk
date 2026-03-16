<?php

namespace OneToMany\LlmSdk\Resource\Mock;

use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Resource\Mock\Trait\GenerateIdTrait;
use OneToMany\LlmSdk\Response\Query\CompileResponse;

final readonly class QueriesResource implements QueriesResourceInterface
{
    use GenerateIdTrait;

    public function __construct()
    {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
     */
    public function compile(CompileRequest $request): CompileResponse
    {
        $requestContent = [
            'model' => $request->getModel()->getId(),
        ];

        // Dimensions Input
        if ($dimensions = $request->getDimensions()) {
            $requestContent['dimensions'] = $dimensions;
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
            $requestContent['instructions'] = $text->getText();
        }

        // Schema Input
        if (null !== $schema = $request->getSchema()) {
            $requestContent['schema'] = $schema->toArray();
        }

        return new CompileResponse($request->getModel(), $requestContent);
    }
}
