<?php

namespace OneToMany\AI\Contract\Action\File;

use OneToMany\AI\Contract\Request\File\CacheFileRequestInterface;
use OneToMany\AI\Contract\Response\File\CachedFileResponseInterface;

interface CacheFileActionInterface
{
    public function act(CacheFileRequestInterface $request): CachedFileResponseInterface;
}
