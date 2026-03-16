<?php

namespace OneToMany\LlmSdk\Resource\Mock;

use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Resource\Mock\Trait\GenerateIdTrait;
use OneToMany\LlmSdk\Response\Query\CompileResponse;
use OneToMany\LlmSdk\Response\Query\Content\GenerateResponse;

use function json_encode;
use function random_int;

final readonly class QueriesResource implements QueriesResourceInterface
{
    use GenerateIdTrait;

    private \Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
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

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
     */
    public function generate(ExecuteRequest $request): GenerateResponse
    {
        $response = [
            'id' => $this->generateId('query'),
            'text' => $this->faker->sentence(),
        ];

        $output = $response['text'];

        if (isset($request->getRequest()['schema'])) {
            $output = json_encode(['output' => $output]);
        }

        return new GenerateResponse($request->getModel(), $response['id'], (string) $output, $response, random_int(100, 10000));
    }
}
