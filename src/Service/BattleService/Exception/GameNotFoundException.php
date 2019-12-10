<?php

namespace App\Service\BattleService\Exception;

class GameNotFoundException extends \Exception implements ExceptionInterface
{
    /**
     * @return GameNotFoundException
     */
    public static function idNotFound(): self
    {
        return new self(sprintf('Game with this id does not exist'));
    }
}
