<?php

namespace App\Service;

use App\Entity\ConfigItem;
use App\Repository\ConfigItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ConfigService
{

    public const CACHE_KEY = "app_config";

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ConfigItemRepository $repository,
        private readonly CacheInterface $appCache,
        private readonly GenericFileUploader $fileUploader
    ) {
    }

    public function getConfig(): array
    {
        return $this->appCache->get(self::CACHE_KEY, function (ItemInterface $item) {
            $item->expiresAfter(86400); // 1day

            return $this->computeConfig();
        });
    }

    public function getConfigItem(string $key): ?string
    {
        $config = $this->getConfig();

        return array_key_exists($key, $config) ? $config[$key] : null;
    }

    public function setConfigKey(string $key, string $value): ConfigItem
    {
        $configItem = $this->repository->findOneByConfigKey($key);
        if (!$configItem) {
            $configItem = new ConfigItem();
            $configItem->setConfigKey($key);
            $this->em->persist($configItem);
        }
        $configItem->setValue($value);
        $this->em->flush();
        $this->appCache->delete(self::CACHE_KEY);

        return $configItem;
    }

    public function computeConfig(): array
    {
        $config = [];
        foreach ($this->repository->findAll() as $c) {
            $config[$c->getConfigKey()] = $c->getValue();
        }

        return $config;
    }

    public function handleConfigFormSubmit(FormInterface $form): void
    {
        foreach ($form->getData() as $key => $value) {
            if (str_starts_with($key, '_')) {
                continue;
            }
            $this->setConfigKey($key, $value);
        }

        if ($form->has('logo')) {

            $file = $form->get('logo')->getData();
            if (!$file) {
                return;
            }
            $value = $this->fileUploader->upload($file);
            $this->setConfigKey('logo', $value);
        }

    }

}
