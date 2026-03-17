<?php

namespace OneToMany\LlmSdk\Resource\OpenAi\Type\Request\File;

use OneToMany\LlmSdk\Exception\RuntimeException;

use function array_merge;
use function fopen;
use function sprintf;
use function strtolower;

final readonly class UploadFile
{
    /**
     * @param non-empty-string $path
     * @param ?non-empty-string $purpose
     * @param ?positive-int $expiresSeconds
     */
    public function __construct(
        public string $path,
        public ?string $purpose = null,
        public ?int $expiresSeconds = null,
    ) {
    }

    /**
     * @return non-empty-lowercase-string
     */
    public function getPurpose(): string
    {
        return strtolower($this->purpose ?? 'user_data');
    }

    /**
     * @return array{
     *   file: resource,
     *   purpose: non-empty-lowercase-string,
     *   expires_after?: array{
     *     anchor: 'created_at',
     *     seconds: positive-int,
     *   },
     * }
     */
    public function toArray(): array
    {
        $array = [
            'file' => $this->openFile(),
            'purpose' => $this->getPurpose(),
        ];

        if ($seconds = $this->expiresSeconds) {
            $array = array_merge($array, [
                'expires_after' => [
                    'anchor' => 'created_at',
                    'seconds' => $seconds,
                ],
            ]);
        }

        return $array;
    }

    /**
     * @return resource
     */
    private function openFile(): mixed
    {
        return @fopen($this->path, 'r') ?: throw new RuntimeException(sprintf('The file "%s" could not be opened.', $this->path));
    }
}
