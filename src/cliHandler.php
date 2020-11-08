<?php

namespace DiffTool\cliHandler;

use Docopt;

use function DiffTool\actions\getFilesDiffText;

const VERSION = '0.0.1';
const DOC = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <format>] <pathToOriginalFile> <pathToModifiedFile>

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <format>             Report format [default: stylish]
DOC;

function handle()
{
    $docOptResponse = Docopt::handle(DOC, ['version' => VERSION]);
    $pathToOriginalFile = $docOptResponse->args['<pathToOriginalFile>'];
    $pathToModifiedFile = $docOptResponse->args['<pathToModifiedFile>'];
    $format = $docOptResponse->args['--format'];
    return getFilesDiffText($pathToOriginalFile, $pathToModifiedFile, $format);
}
