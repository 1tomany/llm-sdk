<?php

namespace OneToMany\LlmSdk\Resource\Mock;

use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\Component\FileUriComponent;
use OneToMany\LlmSdk\Request\Query\Component\PromptComponent;
use OneToMany\LlmSdk\Request\Query\Component\SchemaComponent;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Resource\Mock\Trait\GenerateIdTrait;
use OneToMany\LlmSdk\Response\Query\CompileResponse;
use OneToMany\LlmSdk\Response\Query\ContentResponse;
use OneToMany\LlmSdk\Response\Query\EmbedResponse;
use OneToMany\LlmSdk\Response\Query\ExecuteResponse;

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
        $url = $this->generateUrl($request->getModel()->isEmbedding() ? 'embed' : 'generate');

        $requestContent = [
            'model' => $request->getModel(),
        ];

        if ($dimensions = $request->getDimensions()) {
            $requestContent['dimensions'] = $dimensions;
        }

        foreach ($request->getComponents() as $component) {
            if ($component instanceof PromptComponent) {
                $requestContent['contents'][] = [
                    'text' => $component->getPrompt(),
                    'role' => $component->getRole()->getValue(),
                ];
            }

            if ($component instanceof FileUriComponent) {
                $requestContent['contents'][] = [
                    'fileUri' => $component->getUri(),
                ];
            }

            if ($component instanceof SchemaComponent) {
                $requestContent['schema'] = [
                    'name' => $component->getName(),
                    'schema' => $component->getSchema(),
                    'format' => $component->getFormat(),
                ];
            }
        }

        return new CompileResponse($request->getModel(), $url, $this->convertIfBatchRequest($request->getBatchKey(), $requestContent));
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
     */
    public function execute(ExecuteRequest $request): ExecuteResponse
    {
        $runtime = random_int(100, 10000);

        if ($request->getModel()->isEmbedding()) {
            $embedding = [];

            if (isset($request->getRequest()['dimensions'])) {
                $dimensions = $request->getRequest()['dimensions'];
            }

            $dimensions = !is_int($dimensions ?? null) ? 1024 : max(1, $dimensions);

            for ($i = 0; $i < $dimensions; ++$i) {
                $embedding[] = $this->faker->randomFloat();
            }

            return new EmbedResponse($request->getModel(), $embedding, $runtime);
        }
        $response = [
            'id' => $this->generateId('query'),
            'text' => $this->faker->sentence(),
        ];

        $output = $response['text'];

        if (isset($request->getRequest()['schema'])) {
            $output = json_encode(['output' => $output]);
        }

        return new ContentResponse($request->getModel(), $response['id'], (string) $output, $response, $runtime);
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
    private function generateUrl(string ...$paths): string
    {
        return sprintf('https://mock-llm.service/api/%s', ltrim(implode('/', $paths), '/'));
    }
}
