<?php

namespace Notifications\Tests\Application\Service\FileManager;

use Notifications\Domain\Exceptions\BadDataClassException;
use Notifications\Application\Service\FileManager\ObtainData;
use PHPUnit\Framework\TestCase;

class ObtainDataTest extends TestCase
{
    private const EXCEPTION_FILE_NOT_FOUND = 'File not found: ';
    private const EXCEPTION_DECODING_ERROR = 'Error decoding JSON in: ';
    private const EXCEPTION_FORMAT_ERROR = 'Invalid JSON structure';

    protected ObtainData $dataMethod;

    protected function setUp(): void
    {
        $this->dataMethod = new ObtainData();
    }


    public function testReadingFileExist(): void
    {
        $nameFile = 'valid_file.json';
        $dataWaited = ['key' => 'value'];

        file_put_contents($nameFile, json_encode($dataWaited));

        $dataReaded = $this->dataMethod->readJSON(__DIR__, $nameFile);

        $this->assertEquals($dataWaited, $dataReaded);

        unlink($nameFile);
    }

    public function testReadingFileMissing(): void
    {
        $nameFile = 'missing_file.json';

        $this->expectException(BadDataClassException::class);
        $this->expectExceptionMessage(self::EXCEPTION_FILE_NOT_FOUND . $nameFile);

        $this->dataMethod->readJSON(__DIR__, $nameFile);
    }


    public function testReadingFileFailed(): void
    {
        $nameFile = 'invalid_file.json';

        file_put_contents($nameFile, "This is not a JSON");

        $this->expectException(BadDataClassException::class);
        $this->expectExceptionMessage(self::EXCEPTION_DECODING_ERROR . $nameFile);
        $this->dataMethod->readJSON(__DIR__, $nameFile);

        unlink($nameFile);
    }

    public function testFormatFileWrong(): void
    {
        $this->expectException(BadDataClassException::class);
        $this->expectExceptionMessage(self::EXCEPTION_FORMAT_ERROR);

        $jsonContent = [];

        $this->dataMethod->printFakePublicKey($jsonContent);
    }

    public function testFormatFileWrongContent(): void
    {
        $this->expectException(BadDataClassException::class);
        $this->expectExceptionMessage(self::EXCEPTION_FORMAT_ERROR);

        $jsonContent = [
            'choices' => [
                ['message' => ['content' => 123]]
            ]
        ];

        $this->dataMethod->printFakePublicKey($jsonContent);
    }

    public function testFormatFileMissingContent(): void
    {
        $this->expectException(BadDataClassException::class);
        $this->expectExceptionMessage(self::EXCEPTION_FORMAT_ERROR);

        $jsonContent = [
            'choices' => [
                ['message' => ['other_key' => 'value']]
            ]
        ];

        $this->dataMethod->printFakePublicKey($jsonContent);
    }

    public function testFormatFileMissingChoices(): void
    {
        $this->expectException(BadDataClassException::class);
        $this->expectExceptionMessage(self::EXCEPTION_FORMAT_ERROR);

        $jsonContent = [
            'other_key' => 'value'
        ];

        $this->dataMethod->printFakePublicKey($jsonContent);
    }
}
