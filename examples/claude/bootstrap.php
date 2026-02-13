<?php

use OneToMany\AI\Client\Claude\FileClient;
use Symfony\Component\HttpClient\HttpClient;

require_once __DIR__.'/../common/functions.php';

$serializer = require __DIR__.'/../serializer.php';

$fileClient = new FileClient($serializer, HttpClient::create(), read_api_key('CLAUDE_API_KEY'));
