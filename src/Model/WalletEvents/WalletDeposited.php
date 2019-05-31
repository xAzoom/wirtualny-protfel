<?php
/**
 * Created by PhpStorm.
 * User: moren
 * Date: 19.05.2018
 * Time: 23:10
 */

namespace Model\WalletEvents;

use Prooph\EventSourcing\AggregateChanged;

class WalletDeposited extends AggregateChanged
{
    public function amount(): String
    {
        return $this->payload['amount'];
    }
}