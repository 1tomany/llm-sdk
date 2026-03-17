<?php

function errorMessage(string $message, string ...$values): never
{
    printf("[ERROR] %s\n", vsprintf($message, $values));
    exit(1);
}
