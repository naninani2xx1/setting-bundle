<?php

declare(strict_types=1);

namespace AmzsCMS\SettingBundle\Controller;

use AmzsCMS\SettingBundle\Constant\SettingRoute;
use AmzsCMS\SettingBundle\Constant\SettingType;
use AmzsCMS\SettingBundle\Entity\Setting;
use AmzsCMS\SettingBundle\Form\SettingForm;
use AmzsCMS\SettingBundle\Service\SettingDataTable;
use AmzsCMS\SettingBundle\Service\SettingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingController extends AbstractController
{
    private $settingService;
    private $manager;
    private $datatable;
    public function __construct(SettingService $settingService, EntityManagerInterface $manager, SettingDataTable $dataTable)
    {
        $this->settingService = $settingService;
        $this->manager = $manager;
        $this->datatable = $dataTable;
    }

    public function index(): Response
    {
        return $this->render('@AmzsSetting/index.html.twig');
    }

    public function data(Request $request): Response
    {
        return $this->json($this->datatable->getData($request));
    }

    public function add(Request $request): Response
    {
        $setting = new Setting();
        $setting->setSettingType(SettingType::SETTING_TYPE_KEY_VALUE);
        $form = $this->createForm(SettingForm::class, $setting, [
            'action' => $this->generateUrl(SettingRoute::ROUTE_ADD),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->isXmlHttpRequest() && empty($request->get('_dynamic_reload'))) {
                try{
                    $this->manager->persist($setting);
                    if($setting->getSettingType() === SettingType::SETTING_TYPE_JSON){
                        $arrCollection = $form->get('settingValue')->getData();
                        $str = json_encode($arrCollection->toArray());
                        $setting->setSettingValue($str);
                    }
                    $this->manager->flush();
                    return $this->json(['success' => true]);
                }catch (\Exception $e){

                }
            }
        }

        $title = 'Add setting';
        return $this->render('@AmzsSetting/add_or_edit.html.twig', [
            'form' => $form->createView(),
            'title' => $title,
        ]);
    }

    public function edit(Request $request, int $id): Response
    {
        $setting = $this->settingService->find($id);
        $form = $this->createForm(SettingForm::class, $setting, [
            'action' => $this->generateUrl(SettingRoute::ROUTE_EDIT, ['id' => $id]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($setting);
            $this->manager->flush();

            return $this->json([
                'success' => true,
                'message' => 'Setting edit successfully.',
            ]);
        }

        $form = $form->createView();
        $title = 'Edit setting';
        return $this->render('@AmzsSetting/add_or_edit.html.twig',
            compact('form', 'setting', 'title')
        );
    }
}
