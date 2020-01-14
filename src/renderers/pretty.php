<?php

namespace DiffTool\renderers\pretty;

use DiffTool\diffTree;

function renderValue($value)
{
    if (is_object($value)) {
        return renderObject($value);
    }
    if (is_array($value)) {
        return '[' . implode(', ', $value) . ']';
    }
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return (string) $value;
}

function getRenderedLinesForObject($object)
{
    $renderedObjectLines = array_map(
        function ($key, $item) use ($level) {
            $renderedValue = renderValue($item);
            // $offset = str_repeat('    ', $level + 1);
            return renderKeyValueLine($key, $renderedValue);
        },
        array_keys((array) $objectToRender),
        (array) $objectToRender
    );
    $renderedObjectContents = implode(",\n", $renderedObjectLines);
    $offset = str_repeat('    ', $level);
    return "{\n{$renderedObjectContents}\n}";
}

function renderKeyValueLine($key, $value, $diffToken = ' ')
{
    return "  {$diffToken} {$key}: {$value}";
}

function getRenderedLinesForDiffNode($node)
{
    $key = diffTree\getKey($node);
    $originalValue = diffTree\getOriginalValue($node);
    $modifiedValue = diffTree\getModifiedValue($node);
    switch (diffTree\getType($node)) {
        case diffTree\DIFF_TYPE_SAME:
            return [renderKeyValueLine($key, renderValue($originalValue), ' ')];
        case diffTree\DIFF_TYPE_ADDED:
            return [renderKeyValueLine($key, renderValue($modifiedValue), '+')];
        case diffTree\DIFF_TYPE_REMOVED:
            return [renderKeyValueLine($key, renderValue($originalValue), '-')];
        case diffTree\DIFF_TYPE_CHANGED:
            return [
                renderKeyValueLine($key, renderValue($originalValue), '-'),
                renderKeyValueLine($key, renderValue($modifiedValue), '+')
            ];
        case diffTree\DIFF_TYPE_OBJECT:
            $collectRenderedLines = function ($acc, $node) {
                return array_merge($acc, getRenderedLinesForDiffNode($node));
            };
            $addOffset = function ($line) {
                return "    {$line}";
            };
            $renderedInnerLines = array_reduce(diffTree\getChildren($node), $collectRenderedLines, []);
            $renderedInnerLinesWithOffset = array_map($addOffset, $renderedInnerLines);
            return array_merge(
                [renderKeyValueLine($key, "{", '')],
                $renderedInnerLinesWithOffset,
                ["}"]
            );
        default:
            [];
    }
}

function renderDiffChildren($diffTree)
{
    $offset = "   ";
    $collectRenderedLines = function ($acc, $node) {
        return array_merge($acc, getRenderedLinesForDiffNode($node));
    };
    $addOffset = function ($line) use ($offset) {
        return "{$offset}{$line}";
    };
    $renderedInnerLines = array_reduce($diffTree, $collectRenderedLines, []);
    // offset
    // $renderedInnerLinesWithOffset = array_map($addOffset, $renderedInnerLines);
    $renderedDiffLines = array_merge(['{'], $renderedInnerLines, ['}']);
// print_r($renderedDiffLines);
    return implode("\n", $renderedDiffLines);
}
