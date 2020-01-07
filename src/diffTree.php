<?php

namespace DiffTool\diffTree;

function makeDiffTree(object $originalData, object $changedData)
{
    $intersectElements = array_intersect_assoc((array)$originalData, (array)$changedData);
    $addedElements = array_diff_assoc((array)$changedData, (array)$originalData);
    $removedElements = array_diff_assoc((array)$originalData, (array)$changedData);
    $diffTree = [];
    foreach ($intersectElements as $key => $value) {
        $diffTree[] = ['type' => 'same', 'key' => $key, 'value' => $value];
    }
    foreach ($removedElements as $key => $value) {
        $diffTree[] = ['type' => 'removed', 'key' => $key, 'value' => $value];
        if (in_array($key, array_keys($addedElements))) {
            $diffTree[] = ['type' => 'added', 'key' => $key, 'value' => $addedElements[$key]];
        }
    }
    foreach ($addedElements as $key => $value) {
        if (in_array($key, array_keys($removedElements))) {
            continue;
        }
        $diffTree[] = ['type' => 'added', 'key' => $key, 'value' => $value];
    }
    return $diffTree;
}
