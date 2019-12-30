<?php

namespace DiffTool\main;

use Docopt;

const VERSION = '0.0.1';
const DOC = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <pathToFile1> <pathToFile2>

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


function run()
{
    $response = Docopt::handle(DOC, ['version' => VERSION]);
    $firstFileContents = file_get_contents($response->args['<pathToFile1>']);
    $secondFileContents = file_get_contents($response->args['<pathToFile2>']);
    $firstFileData = json_decode($firstFileContents, true);
    $secondFileData = json_decode($secondFileContents, true);
    echo getDiffText($firstFileData, $secondFileData);
}
