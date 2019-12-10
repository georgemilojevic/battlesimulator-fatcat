<?php


namespace App\Service\BattleService\Exception;


class ChancesException extends \Exception implements ExceptionInterface
{
    /**
     * @return ChancesException
     */
    public static function unsuccessfulAttack(): self
    {
        return new self(sprintf('Your attack failed! Reload and try again'));
    }
}