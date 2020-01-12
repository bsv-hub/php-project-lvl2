<?php

namespace DiffTool\renderers\json;

use DiffTool\diffTree;

function renderValue($value, $level = 0)
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
    $renderedObjectLines = array_map(
        function ($key, $item) use ($level) {
            $renderedValue = renderValue($item);
            $offset = str_repeat('    ', $level + 1);
            return "{$offset}    {$key}: {$renderedValue}";
        },
        array_keys((array) $value),
        (array) $value
    );
    $renderedObjectContents = implode(",\n", $renderedObjectLines);
    $offset = str_repeat('    ', $level);
    return "{\n{$renderedObjectContents}\n{$offset}    }";
}

function renderDiffTree($diffTree, $level = 0)
{
    $offset = str_repeat('    ', $level);
    $diffTextLines = [];
    $diffTextLines[] = "{";
    foreach ($diffTree as $node) {
        $renderedOriginalValue = renderValue($node['originalValue'], $level);
        $renderedModifiedValue = renderValue($node['modifiedValue'], $level);
        switch ($node['type']) {
            case diffTree\DIFF_TYPE_SAME:
                $diffTextLines[] = "{$offset}    {$node['key']}: {$renderedOriginalValue}";
                break;
            case diffTree\DIFF_TYPE_ADDED:
                $diffTextLines[] = "{$offset}  + {$node['key']}: {$renderedModifiedValue}";
                break;
            case diffTree\DIFF_TYPE_REMOVED:
                $diffTextLines[] = "{$offset}  - {$node['key']}: {$renderedOriginalValue}";
                break;
            case diffTree\DIFF_TYPE_CHANGED:
                $diffTextLines[] = "{$offset}  - {$node['key']}: {$renderedOriginalValue}";
                $diffTextLines[] = "{$offset}  + {$node['key']}: {$renderedModifiedValue}";
                break;
            case diffTree\DIFF_TYPE_OBJECT:
                $renderedObject = renderDiffTree($node['children'], $renderFormat, $level + 1);
                $diffTextLines[] = "{$offset}    {$node['key']}: {$renderedObject}";
                break;
            default:
                break;
        }
    }
    $diffTextLines[] = "{$offset}}";
    return implode("\n", $diffTextLines);
}
