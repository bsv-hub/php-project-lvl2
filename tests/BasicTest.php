<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use function DiffTool\main\getDiffText;

class BasicTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $originalContent = file_get_contents(__DIR__ . '/fixtures/before.json');
        $changedContent = file_get_contents(__DIR__ . '/fixtures/after.json');
        $this->originalData = json_decode($originalContent, true);
        $this->changedData = json_decode($changedContent, true);
        $this->expectedResult = file_get_contents(__DIR__ . '/fixtures/result_for_flat.txt');
    }
    

    public function testFlatJsonFilesDiff()
    {
        $result = getDiffText($this->originalData, $this->changedData);
        $this->assertEquals($this->expectedResult, $result);
    }
    
}
