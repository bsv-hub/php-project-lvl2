<?php

namespace DiffTool\diffTree;

const DIFF_TYPE_SAME = 'same';
const DIFF_TYPE_ADDED = 'added';
const DIFF_TYPE_REMOVED = 'removed';
const DIFF_TYPE_CHANGED = 'changed';
const DIFF_TYPE_OBJECT = 'object';

function makeDiffTree($originalData, $modifiedData)
{
    $diffTree = [];
    $originalDataKeys = array_keys((array) $originalData);
    $modifiedDataKeys = array_keys((array) $modifiedData);
    $addedKeys = array_diff($modifiedDataKeys, $originalDataKeys);
    foreach ($originalData as $key => $originalValue) {
        if (!isset($modifiedData->$key)) {
            $diffTree[] = makeDiffNode(DIFF_TYPE_REMOVED, $key, $originalValue, null);
            continue;
        }
        $modifiedValue = $modifiedData->$key;
        if (is_object($originalValue) && is_object($modifiedValue)) {
            $children = makeDiffTree($originalValue, $modifiedValue);
            $diffTree[] = makeDiffNode(DIFF_TYPE_OBJECT, $key, $originalValue, $modifiedValue, $children);
            continue;
        }
        if ($originalValue !== $modifiedValue) {
            $diffTree[] = makeDiffNode(DIFF_TYPE_CHANGED, $key, $originalValue, $modifiedValue);
            continue;
        }
        $diffTree[] = makeDiffNode(DIFF_TYPE_SAME, $key, $originalValue, $modifiedValue);
    }
    foreach ($addedKeys as $key) {
        $diffTree[] = makeDiffNode(DIFF_TYPE_ADDED, $key, null, $modifiedData->$key);
    }
    return $diffTree;
}

function makeDiffNode($type, $key, $originalValue, $modifiedValue, $children = [])
{
    return compact('type', 'key', 'originalValue', 'modifiedValue', 'children');
}

function getType($node)
{
    return $node['type'];
}

function getKey($node)
{
    return $node['key'];
}

function getOriginalValue($node)
{
    return $node['originalValue'];
}

function getModifiedValue($node)
{
    return $node['modifiedValue'];
}

function getChildren($node)
{
    return $node['children'];
}
