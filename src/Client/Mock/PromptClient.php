<?php

namespace OneToMany\AI\Client\Mock;

use OneToMany\AI\Client\Mock\Trait\GenerateUriTrait;
use OneToMany\AI\Contract\Client\PromptClientInterface;
use OneToMany\AI\Contract\Request\Prompt\SendPromptRequestInterface;
use OneToMany\AI\Contract\Response\Prompt\SentPromptResponseInterface;
use OneToMany\AI\Response\Prompt\SentPromptResponse;

use function json_encode;

final readonly class PromptClient implements PromptClientInterface
{
    use GenerateUriTrait;

    private \Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    public function send(SendPromptRequestInterface $request): SentPromptResponseInterface
    {
        $uri = $this->generateUri('resp');

        /** @var non-empty-string $text */
        $text = json_encode(['tag' => $this->faker->word()]);

        return new SentPromptResponse($request->getVendor(), $request->getModel(), $uri, $text, ['id' => $uri, 'text' => $text]);
    }
}
