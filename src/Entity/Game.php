<?php

namespace App\Entity;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Army", inversedBy="games")
     */
    private $army;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $game_log = [];

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

    public function getArmyId(): ?Army
    {
        return $this->army_id;
    }

    public function setArmyId(?Army $army): self
    {
        $this->army = $army;

        return $this;
    }

    public function getGameLog(): ?array
    {
        return $this->game_log;
    }

    public function setGameLog(?array $game_log): self
    {
        $this->game_log = $game_log;

        return $this;
    }
}
