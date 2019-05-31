<?php
/**
 * Created by PhpStorm.
 * User: moren
 * Date: 19.05.2018
 * Time: 13:46
 */

namespace Infrastructure;

use Model\Wallet;
use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Prooph\EventStore\EventStore;
use Ramsey\Uuid\Uuid;

class WalletRepository extends AggregateRepository implements IWalletRepository
{
    public function __construct(EventStore $eventStore)
    {
        //We inject a Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator that can handle our AggregateRoots
        parent::__construct(
            $eventStore,
            AggregateType::fromAggregateRootClass('Model\Wallet'),
            new AggregateTranslator(),
            null, //We don't use a snapshot store in the example
            null, //Also a custom stream name is not required
            true //But we enable the "one-stream-per-aggregate" mode
        );
    }

    public function save(Wallet $wallet): void
    {
        $this->saveAggregateRoot($wallet);
    }

    public function get(Uuid $uuid): ?Wallet
    {
        return $this->getAggregateRoot($uuid->toString());
    }

}