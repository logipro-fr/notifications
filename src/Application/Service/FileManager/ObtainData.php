<?php

namespace Notifications\Application\Service\FileManager;

use Notifications\Domain\Exceptions\BadDataClassException;

class ObtainData
{
    /**
     * @param string $mainPath
     * @param string $fileToAnalyze
     * @return array<mixed>
     * @throws BadDataClassException
     */
    public function readJSON(string $mainPath, string $fileToAnalyze): array
    {
        $filePath = $mainPath . '/resources/' . $fileToAnalyze;

        $fileContents = @file_get_contents($filePath);
        if ($fileContents === false) {
            throw new BadDataClassException(BadDataClassException::EXCEPTION_FILE_NOT_FOUND . $fileToAnalyze);
        }

        $data = json_decode($fileContents, true);

        if ($data === null && json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            throw new BadDataClassException(BadDataClassException::EXCEPTION_DECODING_ERROR . $fileToAnalyze);
        }
        return $data;
    }
    /**
     * @param array<mixed> $jsonContent
     * @return string
     * @throws BadDataClassException
     */
    public function printFakePublicKey(array $jsonContent): string
    {
        $choices = $jsonContent['choices'] ?? null;

        if (!is_array($choices) || !isset($choices[0]['message']['content'])) {
            throw new BadDataClassException(BadDataClassException::EXCEPTION_FORMAT_ERROR);
        }

        $content = $choices[0]['message']['content'];
        if (!is_string($content)) {
            throw new BadDataClassException(BadDataClassException::EXCEPTION_FORMAT_ERROR);
        }

        return $content;
    }
}
