<?php

namespace OneToMany\AI\Client\Mock;

use OneToMany\AI\Client\Mock\Trait\GenerateUriTrait;
use OneToMany\AI\Contract\Client\FileClientInterface;
use OneToMany\AI\Contract\Request\File\CacheFileRequestInterface;
use OneToMany\AI\Contract\Response\File\CachedFileResponseInterface;
use OneToMany\AI\Response\File\CachedFileResponse;

final readonly class FileClient implements FileClientInterface
{
    use GenerateUriTrait;

    public function __construct()
    {
    }

    public function cache(CacheFileRequestInterface $request): CachedFileResponseInterface
    {
        return new CachedFileResponse($request->getVendor(), $this->generateUri('file'));
    }
}
