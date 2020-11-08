<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use function DiffTool\actions\getFilesDiffText;
use function Safe\file_get_contents;

class FlatDiffTest extends TestCase
{
    private string $expectedFlatDiffResult;

    protected function setUp(): void
    {
        parent::setUp();
        $this->expectedFlatDiffResult = file_get_contents(__DIR__ . '/fixtures/flat_diff_result.txt');
    }

    public function testFlatJsonFilesDiff(): void
    {
        $pathToOriginalFile = __DIR__ . '/fixtures/original_flat.json';
        $pathToModifiedFile = __DIR__ . '/fixtures/modified_flat.json';

        $result = getFilesDiffText($pathToOriginalFile, $pathToModifiedFile);

        $this->assertEquals($this->expectedFlatDiffResult, $result);
    }

    public function testFlatYamlFilesDiff(): void
    {
        $pathToOriginalFile = __DIR__ . '/fixtures/original_flat.yml';
        $pathToModifiedFile = __DIR__ . '/fixtures/modified_flat.yml';

        $result = getFilesDiffText($pathToOriginalFile, $pathToModifiedFile);

        // $this->assertEquals($this->expectedFlatDiffResult, $result);
    }
}
