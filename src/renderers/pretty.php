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

function renderObject($objectToRender)
{
    $level = 1;
    $renderedObjectLines = array_map(
        function ($key, $item) use ($level) {
            $renderedValue = renderValue($item);
            $offset = str_repeat('    ', $level + 1);
            return "{$offset}{$key}: {$renderedValue}";
        },
        array_keys((array) $objectToRender),
        (array) $objectToRender
    );
    $renderedObjectContents = implode(",\n", $renderedObjectLines);
    $offset = str_repeat('    ', $level);
    return "{\n{$renderedObjectContents}\n{$offset}}";
}

function renderDiffLine($key, $value, $sign = '', $offsetLevel = 0)
{
    $offset = str_repeat('    ', $offsetLevel);
    return "{$offset}  {$sign} {$key}: {$value}";
}

function renderDiffNode($node)
{
    // print_r($node);
    $level = 1;
    $offset = '---';
    $renderedOriginalValue = renderValue($node['originalValue'], $level);
    $renderedModifiedValue = renderValue($node['modifiedValue'], $level);

    switch ($node['type']) {
        case diffTree\DIFF_TYPE_SAME:
            return renderDiffLine($node['key'], $renderedOriginalValue, ' ', $level);
        case diffTree\DIFF_TYPE_ADDED:
            return renderDiffLine($node['key'], $renderedModifiedValue, '+', $level);
        case diffTree\DIFF_TYPE_REMOVED:
            return renderDiffLine($node['key'], $renderedOriginalValue, '-', $level);
        case diffTree\DIFF_TYPE_CHANGED:
            return renderDiffLine($node['key'], $renderedOriginalValue, '-', $level);
            return renderDiffLine($node['key'], $renderedModifiedValue, '+', $level);
        case diffTree\DIFF_TYPE_OBJECT:
            $renderedObject = renderDiffTree($node['children'], $level + 1);
            return renderDiffLine($node['key'], $renderedObject, ' ', $level);
        default:
        // todo redo
            return '';
    }
}

function renderDiffTree($diffTree)
{
    $offset = "   ";
    $nodeRendererFunctionName = __NAMESPACE__ . '\renderDiffNode';
    $renderedInnerLines = array_map($nodeRendererFunctionName, $diffTree);
    $renderedDiffLines = array_merge(['{'], $renderedInnerLines, ["{$offset}}"]);
// print_r($renderedDiffLines);
    return implode("\n", $renderedDiffLines);
}
