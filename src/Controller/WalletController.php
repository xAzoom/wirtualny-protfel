<?php
/**
 * Created by PhpStorm.
 * User: moren
 * Date: 19.05.2018
 * Time: 05:05
 */

namespace Controller;

use Exceptions\WalletException;
use Infrastructure\WalletRepository;
use Model\Wallet;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class WalletController
{
    protected $eventStore;

    public function __construct($eventStore = null)
    {
        $this->eventStore = $eventStore;
    }

    public function CreateWallet(): Response
    {
        $walletRepository = new WalletRepository($this->eventStore);

        $wallet = Wallet::Create('PLN');

        $walletRepository->save($wallet);

        $jsonResponse = array("id" => $wallet->getId());
        $jsonResponse = json_encode($jsonResponse, JSON_PRETTY_PRINT);

        return new Response($jsonResponse, 201);
    }

    public function GetBalance(string $id): Response
    {
        $walletRepository = new WalletRepository($this->eventStore);

        $loadedWallet = $walletRepository->get(Uuid::fromString($id));

        if (!isset($loadedWallet)) {
            return new Response('Not Found', 404);
        }

        $jsonResponse = array("ballance" => $loadedWallet->getBalance()->getAmount());
        $jsonResponse = json_encode($jsonResponse, JSON_PRETTY_PRINT);

        return new Response($jsonResponse, 200);
    }

    public function Deposit(string $id, int $amount): Response
    {
        $walletRepository = new WalletRepository($this->eventStore);

        $loadedWallet = $walletRepository->get(Uuid::fromString($id));

        if (!isset($loadedWallet)) {
            return new Response('Not Found', 404);
        }

        try {
            $loadedWallet->Deposit($amount);
        } catch (WalletException $e) {
            return new Response($e->getMessage(), 406);
        }

        $walletRepository->save($loadedWallet);

        return new Response("No content", 204);
    }

    public function Withdraw(string $id, int $amount): Response
    {
        $walletRepository = new WalletRepository($this->eventStore);

        $loadedWallet = $walletRepository->get(Uuid::fromString($id));
        if (!isset($loadedWallet)) {
            return new Response('Not Found', 404);
        }

        try {
            $loadedWallet->Withdraw($amount);
        } catch (WalletException $e) {
            return new Response($e->getMessage(), 406);
        }

        $walletRepository->save($loadedWallet);

        return new Response("No content", 204);
    }

    public function Activate(string $id): Response
    {
        $walletRepository = new WalletRepository($this->eventStore);

        $loadedWallet = $walletRepository->get(Uuid::fromString($id));

        if (!isset($loadedWallet)) {
            return new Response('Not Found', 404);
        }

        try {
            $loadedWallet->Activate();
        } catch (WalletException $e) {
            return new Response($e->getMessage(), 406);
        }

        $walletRepository->save($loadedWallet);

        return new Response("No content", 204);
    }

    public function Deactivate(string $id): Response
    {
        $walletRepository = new WalletRepository($this->eventStore);

        $loadedWallet = $walletRepository->get(Uuid::fromString($id));

        if (!isset($loadedWallet)) {
            return new Response('Not Found', 404);
        }

        try {
            $loadedWallet->Deactivate();
        } catch (WalletException $e) {
            return new Response($e->getMessage(), 406);
        }

        $walletRepository->save($loadedWallet);

        return new Response("No content", 204);
    }
}