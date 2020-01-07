<?php

namespace DiffTool\renderers;

function renderDiffTree($diffTree, $format = 'plain')
{
    // print_r($diffTree);
    $diffTypeSigns = [
        'same' => ' ',
        'added' => '+',
        'removed' => '-',
    ];
    $diffTextLines = [];
    $diffTextLines[] = "{";
    foreach ($diffTree as $node) {
        ['key' => $key, 'value' => $value, 'type' => $diffType] = $node;
        $offset = '  ';
        $diffTypeSign = $diffTypeSigns[$diffType] ?? ' ';
        $diffTextLines[] = "{$offset}{$diffTypeSign} {$key}: {$value}";
    }
    $diffTextLines[] = "}";
    return implode("\n", $diffTextLines);
}
