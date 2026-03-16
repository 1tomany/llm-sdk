<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Contract\Resource\OutputsResourceInterface;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Request\Output\GenerateOutputRequest;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Error\Error;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Response\Response;
use OneToMany\LlmSdk\Response\Output\GenerateOutputResponse;
use OneToMany\LlmSdk\Response\Output\Usage\TokenUsage;
use Symfony\Component\Stopwatch\Stopwatch;

use function sprintf;

final readonly class OutputsResource extends BaseResource implements OutputsResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\OutputsResourceInterface
     *
     * @throws RuntimeException when the model returns an error
     * @throws RuntimeException when the model fails to generate any output
     */
    public function generate(GenerateOutputRequest $request): GenerateOutputResponse
    {
        $timer = new Stopwatch(true)->start('generate');

        try {
            $url = $this->buildUrl('responses');

            /** @var array<string, mixed> $content */
            $content = $this->doPostRequest($url, [
                'auth_bearer' => $this->getApiKey(),
                'json' => [
                    ...$request->getRequest(),
                ],
            ]);

            $response = $this->doDenormalize($content, Response::class);

            if ($response->error instanceof Error) {
                throw new RuntimeException($response->error->message);
            }

            if (!$output = $response->getOutput()) {
                throw new RuntimeException(sprintf('The model "%s" failed to generate any output.', $request->getModel()->getValue()));
            }
        } finally {
            $timer->stop();
        }

        return new GenerateOutputResponse($request->getModel(), $response->id, $content, $output, $timer->getDuration(), new TokenUsage($response->usage->getInputTokens(), $response->usage->getCachedTokens(), $response->usage->getOutputTokens()));
    }
}
