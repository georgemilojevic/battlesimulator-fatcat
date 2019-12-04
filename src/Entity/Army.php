<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArmyRepository")
 */
class Army
{
    const ATTACK_RANDOM = 'random';
    const ATTACK_WEAKEST = 'weakest';
    const ATTACK_STRONGEST = 'strongest';
    const MIN_UNITS = 80;
    const MAX_UNITS = 100;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $units;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $attack_strategy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUnits(): ?int
    {
        return $this->units;
    }

    public function setUnits(int $units): self
    {
        if ($units >= self::MIN_UNITS && $units <= self::MAX_UNITS) {
            $this->units = $units;
            return $this;
        }
    }

    public function getAttackStrategy(): ?string
    {
        return $this->attack_strategy;
    }

    public function setAttackStrategy(string $attack_strategy): self
    {
        if (!in_array($attack_strategy, array(self::ATTACK_RANDOM, self::ATTACK_WEAKEST, self::ATTACK_STRONGEST))) {
            throw new \InvalidArgumentException("Invalid Attack Type");
        }
        $this->attack_strategy = $attack_strategy;

        return $this;
    }
}
