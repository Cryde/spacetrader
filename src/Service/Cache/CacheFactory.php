<?php

namespace App\Service\Cache;

use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;
use Symfony\Contracts\Cache\CacheInterface;

class CacheFactory
{
    public function create(): CacheInterface
    {
        return new FilesystemTagAwareAdapter(
            'file_cache',
            60,
            __DIR__ . '/../../../var/cache/'
        );
    }
}