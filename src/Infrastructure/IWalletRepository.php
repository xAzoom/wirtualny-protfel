<?php
/**
 * Created by PhpStorm.
 * User: moren
 * Date: 19.05.2018
 * Time: 13:44
 */

namespace Infrastructure;

use Model\Wallet;
use Ramsey\Uuid\Uuid;

interface IWalletRepository
{
    public function save(Wallet $user): void;
    public function get(Uuid $uuid): ?Wallet;
}