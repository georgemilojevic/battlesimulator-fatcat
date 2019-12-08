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

    /** @var ArmyRepository $armyRepository */
    private $armyRepository;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="army_id")
     */
    private $games;

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

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->setArmyId($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->contains($game)) {
            $this->games->removeElement($game);
            // set the owning side to null (unless already changed)
            if ($game->getArmyId() === $this) {
                $game->setArmyId(null);
            }
        }

        return $this;
    }

    public function findByIdDesc()
    {
        return $this->armyRepository->findOneBy(['id' => 'DESC']);
    }

    public function findByStrategy($strategy, $sortOrder, $limit)
    {
        return $this->armyRepository->findBy([
            'attack_strategy' => $strategy,
        ], ['units' => $sortOrder], $limit);
    }
}
