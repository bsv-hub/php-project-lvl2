<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use function DiffTool\actions\getFilesDiffText;
use function Safe\file_get_contents;

class NestedDiffTest extends TestCase
{
    public function testNestedJsonFilesDiff(): void
    {
        $pathToOriginalFile = __DIR__ . '/fixtures/original_nested.json';
        $pathToModifiedFile = __DIR__ . '/fixtures/modified_nested.json';
        $expectedNestedDiffResult = file_get_contents(__DIR__ . '/fixtures/nested_diff_result.txt');

        $result = getFilesDiffText($pathToOriginalFile, $pathToModifiedFile);

        // $this->assertEquals($expectedNestedDiffResult, $result);
    }
}
