<?php

namespace Gendiff\main;

use Docopt;

const VERSION = '0.0.1';
const DOC = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)

Options:
  -h --help                     Show this screen
  -v --version                  Show version
DOC;

function run()
{
    Docopt::handle(DOC, ['version' => VERSION]);
}
