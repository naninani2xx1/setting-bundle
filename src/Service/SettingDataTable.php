<?php

namespace Amzs\SettingBundle\Service;

use Amzs\CoreBundle\Service\Datatable\BaseDataTable;
use Amzs\SettingBundle\Entity\Setting;
use Amzs\SettingBundle\Repository\SettingRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Asset\Packages;

class SettingDataTable extends BaseDataTable
{
    protected $entityAlias = 'setting';
    private $thumbnailDefaultPath;
    private $package;
    public function __construct(SettingRepository $repository, string $thumbnailDefaultPath, Packages $package)
    {
        parent::__construct($repository);
        $this->thumbnailDefaultPath = $thumbnailDefaultPath;
        $this->package = $package;
    }

    // ================== Tùy chỉnh QueryBuilder từ đầu (nếu cần JOIN) ==================
    protected function createBaseQueryBuilder(): QueryBuilder
    {
        return $this->repository->createQueryBuilder('setting')
            // ->leftJoin('e.category', 'c')
            // ->addSelect('c');
            ;
    }

    protected function applyCustomFilters(QueryBuilder $qb, Request $request): void
    {

    }

    protected function getColumnMap(): array
    {
        return [
            0 => 'id',
//            1 => 'name',
//            2 => 'url',
//            3 => 'language',
        ];
    }

    protected function getSearchableFields(): array
    {
        return ['settingKey'];
    }

    protected function formatData(array $entities): array
    {
        $data = [];
        /** @var Setting $setting */
        foreach ($entities as $index => $setting) {
            $val = $setting->getSettingValue();
            $data[] = [
                'index'         => $index + 1,
                'id'            => $setting->getId(),
                'setting_key'   => $setting->getSettingKey(),
                'setting_value' => $setting->isSettingTypeJson()
                    ? json_decode($val, true)
                    : ($setting->isSettingTypeImage()
                        ? (empty($val) ? $this->package->getUrl($this->thumbnailDefaultPath): $val)
                        : $val),
                'setting_type'  => $setting->getSettingType(),
                'created_at'    => $setting->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at'    => $setting->getUpdatedAt()->format('Y-m-d H:i:s'),
            ];
        }
        return $data;
    }
}