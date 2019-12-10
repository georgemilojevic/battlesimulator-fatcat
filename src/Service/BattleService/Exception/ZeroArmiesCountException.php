<?php


namespace App\Service\BattleService\Exception;


class ZeroArmiesCountException extends \Exception implements ExceptionInterface
{
    /**
     * @return ZeroArmiesCountException
     */
    public static function noArmiesLeftStanding(): self
    {
        return new self(sprintf('All armies have been defeated'));
    }
}