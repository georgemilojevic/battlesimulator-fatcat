<?php


namespace App\Service\BattleService\Exception;


class NotEnoughArmiesException extends \Exception implements ExceptionInterface
{
    /**
     * @return NotEnoughArmiesException
     */
    public static function noArmiesAdded(): self
    {
        return new self(sprintf('You haven\'t added any armies'));
    }

    /**
     * @return NotEnoughArmiesException
     */
    public static function lessThanTenArmies(): self
    {
        return new self(sprintf('Army count must be at least 10 in game'));
    }

    /**
     * @return NotEnoughArmiesException
     */
    public static function lessThanFiveArmies(): self
    {
        return new self(sprintf('At least 5 armies need to join before the battle begins. GamStatus: In Progress'));
    }
}