<?php

namespace DiffTool\renderers;

use DiffTool\diffTree;
use Tightenco\Collect\Support\Collection;

function renderValue($value): string
{
    if (is_object($value)) {
        return "(object)";
    }

    if (is_array($value)) {
        return '[array]';
    }

    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    return (string) $value;
}

function renderDiffTree(Collection $diffTree, string $renderFormat = 'plain'): string
{
    $diffTextLines = $diffTree->map(function ($node) {
        $nodeType = diffTree\getNodeType($node);
        $nodeKey = diffTree\getNodeKey($node);
        $renderedNodeValue = renderValue(diffTree\getNodeValue($node));
        $renderedNodePreviousValue = renderValue(diffTree\getNodePreviousValue($node));

        switch ($nodeType) {
            case diffTree\DIFF_TYPE_SAME:
                return "    {$nodeKey}: {$renderedNodeValue}";
            case diffTree\DIFF_TYPE_ADDED:
                return "  + {$nodeKey}: {$renderedNodeValue}";
            case diffTree\DIFF_TYPE_REMOVED:
                return "  - {$nodeKey}: {$renderedNodeValue}";
            case diffTree\DIFF_TYPE_CHANGED:
                return "  - {$nodeKey}: {$renderedNodePreviousValue}\n"
                    . "  + {$nodeKey}: {$renderedNodeValue}";
            default:
                // FIXME
                throw new \Exception("Can't parse node type");
        }
    });
    return Collection::make('{')->merge($diffTextLines)->merge("}\n")->implode("\n");
}
