<?php
/**
 * Created by PhpStorm.
 * User: moren
 * Date: 19.05.2018
 * Time: 05:17
 */

namespace Model;

use Exceptions\WalletException;
use Model\WalletEvents\WalletActivated;
use Model\WalletEvents\WalletCreated;
use Model\WalletEvents\WalletDeactivated;
use Model\WalletEvents\WalletDeposited;
use Model\WalletEvents\WalletWithdrew;
use Money\Currency;
use Money\Money;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Ramsey\Uuid\Uuid;

class Wallet extends AggregateRoot
{

    /** @var Uuid */
    private $id;

    /** @var Money */
    private $balance;

    /** @var bool */
    private $isActivate = false;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getBalance(): Money
    {
        return $this->balance;
    }

    public function getIsActivate(): bool
    {
        return $this->isActivate;
    }

    public static function Create(string $currency): Wallet
    {
        $id = Uuid::uuid4();

        $instance = new self();

        $instance->recordThat(WalletCreated::occur($id->toString(), ['currency' => $currency]));

        return $instance;
    }

    /**
     * @param int $amount
     * @throws WalletException
     */
    public function Deposit(int $amount): void
    {
        if($amount < 0) {
            throw new WalletException("Can't deposit negative amount.");
        }

        if(!$this->isActivate) {
            throw new WalletException("Can't deposit money when wallet is deactivate.");
        }

        $this->recordThat(WalletDeposited::occur($this->id->toString(), ['amount' => $amount]));
    }

    /**
     * @param int $amount
     * @throws WalletException
     */
    public function Withdraw(int $amount): void
    {
        if($amount < 0) {
            throw new WalletException("Can't withdraw negative amount.");
        }

        if($amount > $this->balance->getAmount()) {
            throw new WalletException("Don't have that much money in your wallet to withdraw it.");
        }

        if(!$this->isActivate) {
            throw new WalletException("Can't withdraw money when wallet is deactivate.");
        }

        $this->recordThat(WalletWithdrew::occur($this->id->toString(), ['amount' => $amount]));
    }

    /**
     * @throws WalletException
     */
    public function Activate(): void
    {
        if ($this->isActivate) {
            throw new WalletException("You can't activate wallet which is activate.");
        }

        $this->recordThat(WalletActivated::occur($this->id->toString(), ['isActivate' => true]));
    }

    /**
     * @throws WalletException
     */
    public function Deactivate(): void
    {
        if (!$this->isActivate) {
            throw new WalletException("You can't deactivate wallet which is deactivate.");
        }

        $this->recordThat(WalletDeactivated::occur($this->id->toString(), ['isActivate' => false]));
    }

    protected function aggregateId(): string
    {
        return $this->id->toString();
    }

    protected function apply(AggregateChanged $event): void
    {
        switch (get_class($event)) {
            case WalletCreated::class:
                $this->createWallet($event);
                break;
            case WalletDeposited::class:
                $this->depositToWallet($event);
                break;
            case WalletWithdrew::class:
                $this->withdrawFromWallet($event);
                break;
            case WalletActivated::class:
                $this->activateWallet($event);
                break;
            case WalletDeactivated::class:
                $this->deactivateWallet($event);
                break;
        }
    }

    private function createWallet(WalletCreated $event): void
    {
        $this->id = Uuid::fromString($event->aggregateId());
        $this->balance = new Money(0, new Currency($event->currency()));
    }

    private function depositToWallet(WalletDeposited $event): void
    {
        $amount = new Money($event->amount(), $this->balance->getCurrency());
        $this->balance = $this->balance->add($amount);
    }

    private function withdrawFromWallet(WalletWithdrew $event): void
    {
        $amount = new Money($event->amount(), $this->balance->getCurrency());
        $this->balance = $this->balance->subtract($amount);
    }

    private function activateWallet(WalletActivated $event) {
        $this->isActivate = $event->isActivated();
    }

    private function deactivateWallet(WalletActivated $event) {
        $this->isActivate = $event->isActivated();
    }
}