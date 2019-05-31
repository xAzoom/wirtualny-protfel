<?php

namespace App;

use Controller\WalletController;

function routesInit($app, $eventStore) {
    $app["wallet.controller"] = function () use ($eventStore) {
        return new WalletController($eventStore);
    };

    $app->post("/", "wallet.controller:CreateWallet");
    $app->get("/{id}", "wallet.controller:GetBalance");
    $app->put("/deposit/{id}/{amount}", "wallet.controller:Deposit");
    $app->put("/withdraw/{id}/{amount}", "wallet.controller:Withdraw");
    $app->put("/activate/{id}", "wallet.controller:Activate");
    $app->put("/deactivate/{id}", "wallet.controller:Deactivate");
}