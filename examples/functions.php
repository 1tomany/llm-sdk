<?php

function successMessage(string $message, string ...$values): void
{
    printf("%s\n", vsprintf($message, $values));
}

function errorMessage(string $message, string ...$values): never
{
    printf("[ERROR] %s\n", vsprintf($message, $values));
    exit(1);
}
