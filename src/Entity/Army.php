<?php

namespace App\Entity;

use App\Repository\ArmyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="float")
     */
    private $units;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $attack_strategy;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="army_id")
     */
    private $games;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Game", inversedBy="armies")
     */
    private $game;

    public function __construct()
    {
        $this->games = new ArrayCollection();
    }

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

    public function getUnits(): ?float
    {
        return $this->units;
    }

    public function setUnits(float $units): self
    {
        $this->units = $units;

        return $this;
    }

    public function getAttackStrategy(): ?string
    {
        return $this->attack_strategy;
    }

    public function setAttackStrategy(string $attack_strategy): self
    {
        $this->attack_strategy = $attack_strategy;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }
}
