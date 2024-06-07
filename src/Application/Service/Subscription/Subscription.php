<?php

namespace Notifications\Application\Service\Subscription;

use Minishlink\WebPush\Notification;
use Notifications\Domain\Entity\Notification\NotificationAddress;
use Notifications\Domain\Entity\Publisher\Publisher;
use Notifications\Domain\Services\KeyGeneratorStrategy;

class Subscription
{
    private NotificationAddress $subscriberInfo;
    /** @var array<string> $subscription */
    private  array $subscription;

    public function __construct()
    {
        $clientSidePushSubscriptionJSON = __DIR__ . '/../../../public/resources/pushSubscription.json';
        $jsonContent = file_get_contents($clientSidePushSubscriptionJSON);
        $subscriberInfo = new NotificationAddress(json_decode($jsonContent, true));
    }


    public function makeAnAction():string
    {
        $subscription = json_decode(file_get_contents('php://input'), true);

        if (!isset($subscription['endpoint'])) {
            echo 'Error: not a subscription';
            return "";
        }
    
        $method = $_SERVER['REQUEST_METHOD'];
    
        switch ($method) {
            case 'POST':
                $this->createSubscription($subscription);
                break;
            case 'PUT':
                $this->getSubscription($subscription);
                break;
            case 'DELETE':
                $this->deleteSubscription($subscription);
                break;
            default:
                echo "Error: method not handled";
                return "";
        }
        return "Method".$method.": Success";
    }

    private function createSubscription($subscription)
    {
        // Ajouter l'abonnement à la base de données
        $stmt = $this->db->prepare("INSERT INTO subscriptions (endpoint, p256dh, auth) VALUES (?, ?, ?)");
        $stmt->execute([
            $subscription['endpoint'],
            $subscription['keys']['p256dh'],
            $subscription['keys']['auth']
        ]);

        echo "Subscription created";
    }

    private function getSubscription($subscription)
    {
        // Mettre à jour l'abonnement dans la base de données
        $stmt = $this->db->prepare("UPDATE subscriptions SET p256dh = ?, auth = ? WHERE endpoint = ?");
        $stmt->execute([
            $subscription['keys']['p256dh'],
            $subscription['keys']['auth'],
            $subscription['endpoint']
        ]);

        echo "Subscription updated";
    }

    private function deleteSubscription($subscription)
    {
        // Supprimer l'abonnement de la base de données
        $stmt = $this->db->prepare("DELETE FROM subscriptions WHERE endpoint = ?");
        $stmt->execute([$subscription['endpoint']]);

        echo "Subscription deleted";
    }

     /**
     * @param array $associativeArray
     * @throws \ErrorException
     */
    public static function create(array $associativeArray): self
    {
        if (array_key_exists('keys', $associativeArray) && is_array($associativeArray['keys'])) {
            return new self(
                $associativeArray['endpoint'],
                $associativeArray['keys']['p256dh'] ?? null,
                $associativeArray['keys']['auth'] ?? null,
                $associativeArray['contentEncoding'] ?? "aesgcm"
            );
        }

        if (array_key_exists('publicKey', $associativeArray) || array_key_exists('authToken', $associativeArray) || array_key_exists('contentEncoding', $associativeArray)) {
            return new self(
                $associativeArray['endpoint'],
                $associativeArray['publicKey'] ?? null,
                $associativeArray['authToken'] ?? null,
                $associativeArray['contentEncoding'] ?? "aesgcm"
            );
        }

        return new self(
            $associativeArray['endpoint']
        );
    }
}
