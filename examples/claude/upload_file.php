<?php

use OneToMany\AI\Request\File\UploadRequest;

require_once __DIR__.'/bootstrap.php';

$filePath = $argv[1] ?? null;

if (!is_string($filePath) || !is_file($filePath)) {
    printf("Usage: php %s <file-path>\n", basename(__FILE__));
}

$response = $fileClient->upload(new UploadRequest('claude-opus-4-6')->atPath($filePath));

print_r($response);
