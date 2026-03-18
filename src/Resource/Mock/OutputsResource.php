<?php

namespace OneToMany\LlmSdk\Resource\Mock;

use OneToMany\LlmSdk\Contract\Resource\OutputsResourceInterface;
use OneToMany\LlmSdk\Request\Query\ProcessQueryRequest;
use OneToMany\LlmSdk\Response\Output\GenerateOutputResponse;
use OneToMany\LlmSdk\Response\Usage\TokenUsage;

use function assert;
use function is_string;
use function json_encode;
use function random_int;
use function strlen;

final readonly class OutputsResource extends BaseResource implements OutputsResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\OutputsResourceInterface
     */
    public function generate(ProcessQueryRequest $request): GenerateOutputResponse
    {
        /**
         * @var array{
         *   id: non-empty-string,
         *   text: non-empty-string,
         * } $response
         */
        $response = [
            'id' => $this->generateId('query'),
            'text' => $this->faker->sentence(),
        ];

        $output = $response['text'];

        if (isset($request->getRequest()['schema'])) {
            /** @var non-empty-string $output */
            $output = json_encode(['output' => $output]);
        }

        return new GenerateOutputResponse($request->getModel(), $response['id'], $response, $output, null, random_int(100, 10000), new TokenUsage(strlen(json_encode($request->getRequest()) ?: ''), 0, strlen($output)));
    }
}
