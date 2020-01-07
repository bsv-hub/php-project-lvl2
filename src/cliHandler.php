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
  gendiff [--format <fmt>] <pathToOriginalFile> <pathToChangedFile>

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <fmt>                Report format [default: plain]
DOC;

function handle()
{
    $docOptResponse = Docopt::handle(DOC, ['version' => VERSION]);
    $pathToOriginalFile = $docOptResponse->args['<pathToOriginalFile>'];
    $pathToChangedFile = $docOptResponse->args['<pathToChangedFile>'];
    $format = $docOptResponse->args['--format'];
    echo getRenderedFilesDiffText($pathToOriginalFile, $pathToChangedFile, $format);
}
