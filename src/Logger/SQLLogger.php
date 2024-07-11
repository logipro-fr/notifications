<?php

// src/Logger/SQLLogger.php
namespace Notifications\Logger;

use Doctrine\DBAL\Logging\SQLLogger as DoctrineSQLLogger;
use Psr\Log\LoggerInterface;

class SQLLogger implements DoctrineSQLLogger
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function startQuery($sql, ?array $params = null, ?array $types = null)
    {
        $this->logger->info($sql, ['params' => $params, 'types' => $types]);
    }

    public function stopQuery()
    {
        // No-op
    }
}
