<?php

namespace App\Entity;

use App\Repository\ConfigItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ConfigItemRepository::class)
 * @UniqueEntity("configKey")
 */
class ConfigItem {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $configKey;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    public function getId(): ?int {
        return $this->id;
    }

    public function getConfigKey(): ?string {
        return $this->configKey;
    }

    public function setConfigKey(string $configKey): self {
        $this->configKey = $configKey;

        return $this;
    }

    public function getValue(): ?string {
        return $this->value;
    }

    public function setValue(string $value): self {
        $this->value = $value;

        return $this;
    }
}
