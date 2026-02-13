<?php

use OneToMany\AI\Client\Claude\FileClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

require_once __DIR__.'/../common/functions.php';

/** @var DenormalizerInterface $serializer */
$serializer = require __DIR__.'/../serializer.php';

$fileClient = new FileClient($serializer, HttpClient::create(), read_api_key('CLAUDE_API_KEY'));
