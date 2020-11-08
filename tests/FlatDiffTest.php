<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use function DiffTool\actions\getFilesDiffText;

class FlatDiffTest extends TestCase
{
    private $expectedFlatDiffResult;

    protected function setUp(): void
    {
        parent::setUp();
        $this->expectedFlatDiffResult = file_get_contents(__DIR__ . '/fixtures/flat_diff_result.txt');
    }

    public function testFlatJsonFilesDiff()
    {
        $pathToOriginalFile = __DIR__ . '/fixtures/original_flat.json';
        $pathToModifiedFile = __DIR__ . '/fixtures/modified_flat.json';

        $result = getFilesDiffText($pathToOriginalFile, $pathToModifiedFile);

        $this->assertEquals($this->expectedFlatDiffResult, $result);
    }

    public function testFlatYamlFilesDiff(): void
    {
        $this->markTestSkipped();
        $pathToOriginalFile = __DIR__ . '/fixtures/original_flat.yml';
        $pathToModifiedFile = __DIR__ . '/fixtures/modified_flat.yml';

        $result = getFilesDiffText($pathToOriginalFile, $pathToModifiedFile);

        $this->assertEquals($this->expectedFlatDiffResult, $result);
    }
}
