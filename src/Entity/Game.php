<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 */
class Game
{
    public const COMPLETED = 'Completed';
    public const IN_PROGRESS = 'In Progress';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Army", mappedBy="game")
     */
    private $armies;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GameLog", mappedBy="game")
     */
    private $gameLogs;

    public function __construct()
    {
        $this->armies = new ArrayCollection();
        $this->gameLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Army[]
     */
    public function getArmies(): Collection
    {
        return $this->armies;
    }

    public function addArmy(Army $army): self
    {
        if (!$this->armies->contains($army)) {
            $this->armies[] = $army;
            $army->setGame($this);
        }

        return $this;
    }

    public function removeArmy(Army $army): self
    {
        if ($this->armies->contains($army)) {
            $this->armies->removeElement($army);
            // set the owning side to null (unless already changed)
            if ($army->getGame() === $this) {
                $army->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GameLog[]
     */
    public function getGameLogs(): Collection
    {
        return $this->gameLogs;
    }

    public function addGameLog(GameLog $gameLog): self
    {
        if (!$this->gameLogs->contains($gameLog)) {
            $this->gameLogs[] = $gameLog;
            $gameLog->setGame($this);
        }

        return $this;
    }

    public function removeGameLog(GameLog $gameLog): self
    {
        if ($this->gameLogs->contains($gameLog)) {
            $this->gameLogs->removeElement($gameLog);
            // set the owning side to null (unless already changed)
            if ($gameLog->getGame() === $this) {
                $gameLog->setGame(null);
            }
        }

        return $this;
    }
}
