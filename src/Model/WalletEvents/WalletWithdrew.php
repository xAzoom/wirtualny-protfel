<?php
/**
 * Created by PhpStorm.
 * User: moren
 * Date: 21.05.2018
 * Time: 13:07
 */

namespace Model\WalletEvents;


use Prooph\EventSourcing\AggregateChanged;

class WalletWithdrew extends AggregateChanged
{
    public function amount(): String
    {
        return $this->payload['amount'];
    }
}