<?php

namespace DiffTool\cliHandler;

use Docopt;

use function DiffTool\actions\getRenderedFilesDiffText;

const VERSION = '0.0.1';
const DOC = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <pathToOriginalFile> <pathToModifiedFile>

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <fmt>                Report format [default: pretty]
DOC;

function handle()
{
    $docOptResponse = Docopt::handle(DOC, ['version' => VERSION]);
    $pathToOriginalFile = $docOptResponse->args['<pathToOriginalFile>'];
    $pathToModifiedFile = $docOptResponse->args['<pathToModifiedFile>'];
    $format = $docOptResponse->args['--format'];
    echo getRenderedFilesDiffText($pathToOriginalFile, $pathToModifiedFile, $format);
}
