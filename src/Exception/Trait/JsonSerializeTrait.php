<?php

namespace OneToMany\LlmSdk\Exception\Trait;

trait JsonSerializeTrait
{
    /**
     * @return array{
     *   message: string,
     *   code: int|string,
     *   file: string,
     *   line: int,
     *   previous: ?array{
     *     message: string,
     *   },
     * }
     */
    public function jsonSerialize(): array
    {
        $record = [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'previous' => null,
        ];

        if ($previous = $this->getPrevious()) {
            $record['previous'] = [
                'message' => $previous->getMessage(),
            ];
        }

        return $record;
    }
}
