<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

use function DiffTool\main\getDiffBetweenFilesAsText;

class BasicTest extends TestCase
{
    private $expectedFlatComparisonResult;

    protected function setUp(): void
    {
        parent::setUp();
        $this->expectedFlatComparisonResult = file_get_contents(__DIR__ . '/fixtures/result_for_flat.txt');
    }
    
    public function testFlatJsonFilesDiff()
    {
        $pathToOriginalFile = __DIR__ . '/fixtures/original_flat.json';
        $pathToChangedFile = __DIR__ . '/fixtures/changed_flat.json';
        $result = getDiffBetweenFilesAsText($pathToOriginalFile, $pathToChangedFile);
        $this->assertEquals($this->expectedFlatComparisonResult, $result);
    }
    
    public function testFlatYamlFilesDiff()
    {
        $pathToOriginalFile = __DIR__ . '/fixtures/original_flat.yml';
        $pathToChangedFile = __DIR__ . '/fixtures/changed_flat.yml';
        $result = getDiffBetweenFilesAsText($pathToOriginalFile, $pathToChangedFile);
        $this->assertEquals($this->expectedFlatComparisonResult, $result);
    }
}
