<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use function DiffTool\actions\getRenderedFilesDiffText;

class NestedDiffTest extends TestCase
{
    public function testNestedJsonFilesDiff()
    {
        $pathToOriginalFile = __DIR__ . '/fixtures/original_nested.json';
        $pathToModifiedFile = __DIR__ . '/fixtures/modified_nested.json';
        $expectedNestedDiffResult = file_get_contents(__DIR__ . '/fixtures/nested_diff_result.txt');
        $result = getRenderedFilesDiffText($pathToOriginalFile, $pathToModifiedFile);
        $this->assertEquals($expectedNestedDiffResult, $result);
    }

    public function testNestedYamlFilesDiff()
    {
        $pathToOriginalFile = __DIR__ . '/fixtures/original_nested.yml';
        $pathToModifiedFile = __DIR__ . '/fixtures/modified_nested.yml';
        $expectedNestedDiffResult = file_get_contents(__DIR__ . '/fixtures/nested_diff_result.txt');
        $result = getRenderedFilesDiffText($pathToOriginalFile, $pathToModifiedFile);
        $this->assertEquals($expectedNestedDiffResult, $result);
    }
}
