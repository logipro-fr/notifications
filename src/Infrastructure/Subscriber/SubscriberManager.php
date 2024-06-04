<?php

namespace Notifications\Infrastructure\Subscriber;

use Notifications\Domain\Entity\Subscriber\Subscriber;
use Notifications\Domain\Entity\Publisher\Publisher;

class SubscriberManager
{
    private const SEPARATOR = ", ";
    private const POINT_SEPARATOR = "; ";
    private const SUB_TITLE_LIST = "Subscribers: ";
    private SubscriberRepository $subscriberRepository;

    public function __construct(SubscriberRepository $subscriberRepository)
    {
        $this->subscriberRepository = $subscriberRepository;
    }

    public function addSubscriber(Subscriber $subscriber): Subscriber
    {
        return $this->subscriberRepository->add($subscriber);
    }

    public function removeSubscriber(Subscriber $subscriber): void
    {
        $this->subscriberRepository->remove($subscriber);
    }
    /**
     * @return array<Subscriber>
     */
    public function getSubscribers(): array
    {
        return $this->subscriberRepository->getAll();
    }

    public function __toString(): string
    {
        $subscriberList = [];
        foreach ($this->subscriberRepository->getAll() as $subscriber) {
            $subscriberId = $subscriber->getSubscriberId();
            $flattenedIds = [];
            foreach ($subscriberId as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $subValue) {
                        $flattenedIds[] = $subValue;
                    }
                } else {
                    $flattenedIds[] = $value;
                }
            }
            $subscriberList[] = implode(self::SEPARATOR, $flattenedIds);
        }

        $filteredSubscriberList = array_filter($subscriberList);
        if (empty($filteredSubscriberList)) {
            return self::SUB_TITLE_LIST; // Return title only if there are no subscribers
        }

        return self::SUB_TITLE_LIST . implode(self::POINT_SEPARATOR, $filteredSubscriberList);
    }

    //public function notifySubscribers(Publisher $publisher): void
    //{
    //    foreach ($this->subscriberRepository->getAll() as $subscriber) {
    //        $subscriber->subscribe($publisher, $subscriber->getSubscriberId());
    //    }
    //}
}
