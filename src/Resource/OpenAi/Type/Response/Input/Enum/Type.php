<?php

namespace OneToMany\LlmSdk\Resource\OpenAi\Type\Response\Input\Enum;

enum Type: string
{
    case File = 'input_file';
    case Image = 'input_image';
    case Text = 'input_text';
    case Schema = 'json_schema';

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return non-empty-lowercase-string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
