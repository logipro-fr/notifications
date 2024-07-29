<?php

namespace Notifications\Infrastructure\Persistence\Subscriber;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Notifications\Domain\Model\Subscriber\Endpoint;
use Notifications\Domain\Model\Subscriber\Subscriber;
use Notifications\Domain\Model\Subscriber\SubscriberRepositoryInterface;
use Notifications\Domain\Exceptions\SubscriberNotFoundException;

/**
 * @extends EntityRepository<Subscriber>
 */
class SubscriberRepositoryDoctrine extends EntityRepository implements SubscriberRepositoryInterface
{
    private const ERROR_MSG = "Error can't find the endpoint %s";
    private const ERROR_CODE = 400;

    public function __construct(EntityManagerInterface $em)
    {
        $class = $em->getClassMetadata(Subscriber::class);
        parent::__construct($em, $class);
    }

    public function add(Subscriber $subscriber): void
    {
        $this->getEntityManager()->persist($subscriber);
    }

    public function findById(Endpoint $searchId): Subscriber
    {
        $subscriber = $this->getEntityManager()->find(Subscriber::class, $searchId);
        if ($subscriber === null) {
            throw new SubscriberNotFoundException(
                sprintf(self::ERROR_MSG, $searchId),
                self::ERROR_CODE
            );
        }
        return $subscriber;
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
