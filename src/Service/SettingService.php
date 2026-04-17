<?php

namespace Amzs\SettingBundle\Service;

use Amzs\SettingBundle\Entity\Setting;
use Amzs\SettingBundle\Repository\SettingRepository;
use Symfony\Contracts\Cache\CacheInterface;

class SettingService
{
    private $repository;
    private $cacheInterface;
    public function __construct(
        SettingRepository $repository,
        CacheInterface $cacheInterface
    )
    {
        $this->repository = $repository;
        $this->cacheInterface = $cacheInterface;
    }

    public function find($id, $lockMode = null, $lockVersion = null): ?Setting
    {
        return $this->repository->find($id, $lockMode, $lockVersion);
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?Setting
    {
        return $this->repository->findOneBy($criteria, $orderBy);
    }
}