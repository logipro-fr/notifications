<?php

namespace Notifications\Infrastructure\Persistence\Subscriber;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Notifications\Domain\Entity\Subscriber\Endpoint;
use Notifications\Domain\Entity\Subscriber\Subscriber;
use Notifications\Domain\Entity\Subscriber\SubscriberRepositoryInterface;
use Notifications\Domain\Exceptions\SubscriberNotFoundException;

/**
 * @extends EntityRepository<Subscriber>
 */
class SubscriberRepositoryDoctrine extends EntityRepository implements SubscriberRepositoryInterface
{
    private const ERROR_MSG = "Error can't find the endpoint %s";

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
                400
            );
        }
        return $subscriber;
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
