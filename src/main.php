<?php

namespace DiffTool\main;

use Docopt;
use ParseError;
use Symfony\Component\Yaml\Yaml;

const VERSION = '0.0.1';
const DOC = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <pathToOriginalFile> <pathToChangedFile>

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <fmt>                Report format [default: plain]
DOC;

function getDiffText($firstFileData, $secondFileData)
{
    $intersectElements = array_intersect_assoc($firstFileData, $secondFileData);
    $addedElements = array_diff_assoc($secondFileData, $firstFileData);
    $removedElements = array_diff_assoc($firstFileData, $secondFileData);
    $diffTextLines = [];
    $diffTextLines[] = "{";
    foreach ($intersectElements as $key => $value) {
        $diffTextLines[] = "    {$key}: {$value}";
    }
    foreach ($removedElements as $key => $value) {
        $diffTextLines[] = "  - {$key}: {$value}";
        if (in_array($key, array_keys($addedElements))) {
            $diffTextLines[] = "  + {$key}: {$addedElements[$key]}";
        }
    }
    foreach ($addedElements as $key => $value) {
        if (in_array($key, array_keys($removedElements))) {
            continue;
        }
        $diffTextLines[] = "  + {$key}: {$addedElements[$key]}";
    }
    $diffTextLines[] = "}";
    return implode("\n", $diffTextLines);
}

function parse($text, $format)
{
    switch ($format) {
        case 'json':
            return json_decode($text, true);
        case 'yaml':
        case 'yml':
            return Yaml::parse($text); // Yaml::PARSE_OBJECT_FOR_MAP
        default:
            throw new ParseError('Unknown format');
    }
}

function getDiffBetweenFilesAsText($pathToOriginalFile, $pathToChangedFile)
{
    $contentsOfOriginalFile = file_get_contents($pathToOriginalFile);
    $contentsOfChangedFile = file_get_contents($pathToChangedFile);
    $extensionOfOriginalFile = pathinfo($pathToOriginalFile, PATHINFO_EXTENSION);
    $extensionOfChangedFile = pathinfo($pathToChangedFile, PATHINFO_EXTENSION);
    $originalData = parse($contentsOfOriginalFile, $extensionOfOriginalFile);
    $changedData = parse($contentsOfChangedFile, $extensionOfChangedFile);
    return getDiffText($originalData, $changedData);
}

function run()
{
    $docOptResponse = Docopt::handle(DOC, ['version' => VERSION]);
    $pathToOriginalFile = $docOptResponse->args['<pathToOriginalFile>'];
    $pathToChangedFile = $docOptResponse->args['<pathToChangedFile>'];
    echo getDiffText($pathToOriginalFile, $pathToChangedFile);
}
