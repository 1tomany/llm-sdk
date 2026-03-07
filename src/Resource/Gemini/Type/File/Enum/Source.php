<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\File\Enum;

enum Source: string
{
    case Generated = 'GENERATED';
    case Registered = 'REGISTERED';
    case Unspecified = 'SOURCE_UNSPECIFIED';
    case Uploaded = 'UPLOADED';

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return non-empty-uppercase-string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
