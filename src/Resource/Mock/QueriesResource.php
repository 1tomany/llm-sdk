<?php

namespace OneToMany\LlmSdk\Resource\Mock;

use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Resource\Mock\Trait\GenerateIdTrait;
use OneToMany\LlmSdk\Response\Query\CompileResponse;
use OneToMany\LlmSdk\Response\Query\Content\EmbedResponse;
use OneToMany\LlmSdk\Response\Query\Content\GenerateResponse;

use function is_int;
use function json_encode;
use function max;
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
        $url = $this->buildUrl($request->getModel()->isEmbedding() ? 'embed' : 'generate');

        $requestContent = [
            'model' => $request->getModel()->getId(),
        ];

        foreach ($request->getFiles() as $file) {
            $requestContent['files'][] = [
                'fileUri' => $file->getUri(),
            ];
        }

        foreach ($request->getPrompts() as $prompt) {
            $requestContent['prompts'][] = [
                'text' => $prompt->getPrompt(),
                'role' => $prompt->getRole()->getValue(),
            ];
        }

        if ($prompt = $request->getInstructions()) {
            $requestContent['instructions'] = $prompt->getPrompt();
        }

        if ($dimensions = $request->getDimensions()) {
            $requestContent['dimensions'] = $dimensions;
        }

        if ($schema = $request->getSchema()) {
            $requestContent['schema'] = [
                'name' => $schema->getName(),
                'schema' => $schema->getSchema(),
                'format' => $schema->getFormat(),
            ];
        }

        return new CompileResponse($request->getModel(), $url, $this->convertIfBatchRequest($request->getBatchKey(), $requestContent));
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

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
     */
    public function embed(ExecuteRequest $request): EmbedResponse
    {
        $embedding = [];

        if (isset($request->getRequest()['dimensions'])) {
            $dimensions = $request->getRequest()['dimensions'];
        }

        $dimensions = !is_int($dimensions ?? null) ? 1024 : max(1, $dimensions);

        for ($i = 0; $i < $dimensions; ++$i) {
            $embedding[] = $this->faker->randomFloat();
        }

        return new EmbedResponse($request->getModel(), $embedding, random_int(100, 10000));
    }

    /**
     * @param ?non-empty-string $batchKey
     * @param array<string, mixed> $request
     *
     * @return array<string, mixed>
     */
    private function convertIfBatchRequest(?string $batchKey, array $request): array
    {
        return null === $batchKey ? $request : ['batchKey' => $batchKey, 'request' => $request];
    }

    /**
     * @param non-empty-string $paths
     *
     * @return non-empty-string
     */
    private function buildUrl(string ...$paths): string
    {
        return sprintf('https://mock-llm.service/api/%s', ltrim(implode('/', $paths), '/'));
    }
}
