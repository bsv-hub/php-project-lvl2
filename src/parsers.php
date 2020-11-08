<?php

namespace DiffTool\parsers;

use ParseError;
use Symfony\Component\Yaml\Yaml;
use Tightenco\Collect\Support\Collection;

const PARSED_TYPE_JSON = 'json';
const PARSED_TYPE_YAML = 'yaml';
const UNSUPPORTED_TYPE_MESSAGE = 'This parsing type is unsupported!';
const UNSUPPORTED_EXTENSION_MESSAGE = 'This file extension is unsupported!';

function parse($text, $type)
{
    if ($type === PARSED_TYPE_JSON) {
        return Collection::make(
            json_decode($text, false, 512, JSON_THROW_ON_ERROR)
        );
    }
    if ($type === PARSED_TYPE_YAML) {
        return Collection::make(
            Yaml::parse($text, Yaml::PARSE_OBJECT_FOR_MAP)
        );
    }
    throw new ParseError(UNSUPPORTED_TYPE_MESSAGE);
}

function getParsedTypeForExtension(string $extension)
{
    $extensionsToTypesMap = [
        'json' => PARSED_TYPE_JSON,
        'yml' => PARSED_TYPE_YAML,
        'yaml' => PARSED_TYPE_YAML
    ];
    if (!array_key_exists($extension, $extensionsToTypesMap)) {
        throw new ParseError(UNSUPPORTED_EXTENSION_MESSAGE);
    }
    return $extensionsToTypesMap[$extension];
}
