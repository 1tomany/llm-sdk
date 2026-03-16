<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Resource\OutputsResourceInterface;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Request\Output\GenerateOutputRequest;
use OneToMany\LlmSdk\Resource\Gemini\Type\Content\Generation;
use OneToMany\LlmSdk\Response\Output\GenerateOutputResponse;
use Symfony\Component\Stopwatch\Stopwatch;

use function sprintf;

final readonly class OutputsResource extends BaseResource implements OutputsResourceInterface
{

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\OutputsResourceInterface
     *
     * @throws RuntimeException when the model fails to generate any output
     */
    public function generate(GenerateOutputRequest $request): GenerateOutputResponse
    {
        $timer = new Stopwatch(true)->start('generate');

        try {
            $url = $this->buildUrl($this->getApiVersion(), sprintf('models/%s:generateContent', $request->getModel()->getId()));

            /** @var array<string, mixed> $response */
            $response = $this->doPostRequest($url, [
                'headers' => $this->buildHeaders(),
                'json' => [
                    ...$request->getRequest(),
                ],
            ]);

            $generation = $this->doDenormalize($response, Generation::class);

            if (!$output = $generation->getOutput()) {
                throw new RuntimeException(sprintf('The model "%s" failed to generate any output.', $request->getModel()->getValue()));
            }
        } finally {
            $timer->stop();
        }

        return new GenerateOutputResponse($request->getModel(), $generation->responseId, $output, $response, $timer->getDuration()); //, new UsageResponse($generation->usageMetadata->promptTokenCount, $generation->usageMetadata->cachedContentTokenCount, $generation->usageMetadata->outputTokenCount));
    }
}
