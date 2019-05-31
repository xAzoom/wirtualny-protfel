<?php
/**
 * Created by PhpStorm.
 * User: moren
 * Date: 21.05.2018
 * Time: 13:42
 */

namespace Model\WalletEvents;


use Prooph\EventSourcing\AggregateChanged;

class WalletActivated extends AggregateChanged
{
    public function isActivated(): bool
    {
        return $this->payload['isActivate'];
    }
}