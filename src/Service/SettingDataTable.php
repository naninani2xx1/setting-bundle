<?php

namespace AmzsCMS\SettingBundle\Service;

use AmzsCMS\CoreBundle\Constant\CoreConfig;
use AmzsCMS\CoreBundle\Service\Datatable\BaseDataTable;
use AmzsCMS\SettingBundle\Entity\Setting;
use AmzsCMS\SettingBundle\Repository\SettingRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Asset\Packages;

class SettingDataTable extends BaseDataTable
{
    protected $entityAlias = 'setting';
    private $package;
    private $parameterBag;
    public function __construct(SettingRepository $repository, Packages $package, ParameterBagInterface $parameterBag)
    {
        parent::__construct($repository);
        $this->package = $package;
        $this->parameterBag = $parameterBag;
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
                        ? (empty($val) ? $this->package->getUrl(
                            $this->parameterBag->get(CoreConfig::PATH_TREE_BUILDER. '.assets_manager.thumbnail_default')
                        ): $val)
                        : $val),
                'setting_type'  => $setting->getSettingType(),
                'created_at'    => $setting->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at'    => $setting->getUpdatedAt()->format('Y-m-d H:i:s'),
            ];
        }
        return $data;
    }
}