<?php

namespace OneToMany\AI\Client\Gemini\File\Enum;

enum Source: string
{
    case Generated = 'GENERATED';
    case Registered = 'REGISTERED';
    case Unspecified = 'SOURCE_UNSPECIFIED';
    case Uploaded = 'UPLOADED';

    /**
     * @return 'Generated'|'Registered'|'Unspecified'|'Uploaded'
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return 'GENERATED'|'REGISTERED'|'SOURCE_UNSPECIFIED'|'UPLOADED'
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
