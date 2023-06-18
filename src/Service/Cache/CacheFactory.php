<?php

namespace App\Service\Cache;

use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class CacheFactory
{
    const TAG_MY_CONTRACTS = 'my_contracts';
    public function create(): TagAwareCacheInterface
    {
        return new FilesystemTagAwareAdapter(
            'file_cache',
            60,
            __DIR__ . '/../../../var/cache/'
        );
    }
}