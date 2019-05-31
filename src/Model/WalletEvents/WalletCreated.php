<?php
/**
 * Created by PhpStorm.
 * User: moren
 * Date: 19.05.2018
 * Time: 13:26
 */

namespace Model\WalletEvents;


use Money\Currency;
use Prooph\EventSourcing\AggregateChanged;

class WalletCreated extends AggregateChanged
{
    public function currency(): String
    {
        return $this->payload['currency'];
    }
}