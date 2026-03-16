<?php

namespace OneToMany\LlmSdk\Resource\Mock;

use OneToMany\LlmSdk\Contract\Resource\OutputsResourceInterface;
use OneToMany\LlmSdk\Request\Output\GenerateOutputRequest;
use OneToMany\LlmSdk\Resource\Mock\Trait\GenerateIdTrait;
use OneToMany\LlmSdk\Response\Output\GenerateOutputResponse;

use function json_encode;
use function random_int;

final readonly class OutputsResource implements OutputsResourceInterface
{
    use GenerateIdTrait;

    private \Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\OutputsResourceInterface
     */
    public function generate(GenerateOutputRequest $request): GenerateOutputResponse
    {
        $response = [
            'id' => $this->generateId('query'),
            'text' => $this->faker->sentence(),
        ];

        $output = $response['text'];

        if (isset($request->getRequest()['schema'])) {
            $output = json_encode(['output' => $output]);
        }

        return new GenerateOutputResponse($request->getModel(), $response['id'], $response, (string) $output, random_int(100, 10000));
    }
}
