<?php

/**
 * @param non-empty-string $name
 *
 * @return non-empty-string
 */
function read_api_key(string $name): string
{
    if (!$apiKey = getenv($name)) {
        printf('Set the "%s" environment variable to continue.'.PHP_EOL, $name);
        exit(1);
    }

    return $apiKey;
}

/**
 * @param non-empty-lowercase-string $default
 *
 * @return non-empty-lowercase-string
 */
function read_model_name(string $default): string
{
    if ($modelId = getenv('MODEL_ID')) {
        return strtolower($modelId);
    }

    return $default;
}
