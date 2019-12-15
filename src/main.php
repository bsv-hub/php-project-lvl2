<?php

namespace Gendiff\main;

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

function run()
{
    $response = Docopt::handle(DOC, ['version' => VERSION]);
    $data1 = json_decode(file_get_contents($response->args['<pathToFile1>']), true);
    $data2 = json_decode(file_get_contents($response->args['<pathToFile2>']), true);
    $intersectElements = array_intersect_assoc($data1, $data2);
    $addedElements = array_diff_assoc($data2, $data1);
    $removedElements = array_diff_assoc($data1, $data2);

    echo "{\n";
    foreach ($intersectElements as $key => $value) {
        echo "    {$key}: {$value}\n";
    }
    foreach ($removedElements as $key => $value) {
        echo "  - {$key}: {$value}\n";
        if (in_array($key, array_keys($addedElements))) {
            echo "  + {$key}: {$addedElements[$key]}\n";
        }
    }
    foreach ($addedElements as $key => $value) {
        if (in_array($key, array_keys($removedElements))) {
            continue;
        }
        echo "  + {$key}: {$addedElements[$key]}\n";
    }
    echo "}\n";

    // print_r($response);
}
