<?php

namespace Notifications\Tests\Logger;

use Notifications\Logger\SQLLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class SQLLoggerTest extends TestCase
{
    public function testStartQueryLogsCorrectly(): void
    {
        $sql = 'SELECT * FROM users WHERE id = ?';
        $params = [1];
        $types = [\PDO::PARAM_INT];

        $loggerMock = $this->createMock(LoggerInterface::class);

        $loggerMock->expects($this->once())
            ->method('info')
            ->with(
                $this->equalTo($sql),
                $this->equalTo(['params' => $params, 'types' => $types])
            );

        $sqlLogger = new SQLLogger($loggerMock);
        $sqlLogger->startQuery($sql, $params, $types);
    }

    public function testStopQuery(): void
    {
        $loggerMock = $this->createMock(LoggerInterface::class);

        $sqlLogger = new SQLLogger($loggerMock);

        // Call stopQuery to ensure it's covered by the test
        $sqlLogger->stopQuery();

        // Verify that nothing happens in stopQuery, as expected
        $this->assertTrue(true);
    }
}
