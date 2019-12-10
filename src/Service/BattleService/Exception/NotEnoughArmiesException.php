<?php


namespace App\Service\BattleService\Exception;


class NotEnoughArmiesException extends \Exception implements ExceptionInterface
{
    /**
     * @return NotEnoughArmiesException
     */
    public static function lessThanTenArmies(): self
    {
        return new self(sprintf('Army count must be at least 10'));
    }

    /**
     * @return NotEnoughArmiesException
     */
    public static function lessThanFiveArmies(): self
    {
        return new self(sprintf('Five armies need to join before calling for Attack'));
    }
}