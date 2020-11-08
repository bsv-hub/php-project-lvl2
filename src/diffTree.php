<?php

namespace DiffTool\diffTree;

use Tightenco\Collect\Support\Collection;

const DIFF_TYPE_SAME = 'same';
const DIFF_TYPE_ADDED = 'added';
const DIFF_TYPE_REMOVED = 'removed';
const DIFF_TYPE_CHANGED = 'changed';
const DIFF_TYPE_OBJECT = 'object';

function makeDiffTree(Collection $originalData, Collection $modifiedData): Collection
{
    $allKeysSorted = $originalData->merge($modifiedData)
        ->keys()
        ->unique()
        ->sort()
        ->values();

    return $allKeysSorted->map(function ($key) use ($originalData, $modifiedData) {
        if (!$modifiedData->has($key)) {
            return makeDiffNode(DIFF_TYPE_REMOVED, $key, $originalData->get($key));
        }

        if (!$originalData->has($key)) {
            return makeDiffNode(DIFF_TYPE_ADDED, $key, $modifiedData->get($key));
        }

        if ($originalData->get($key) !== $modifiedData->get($key)) {
            return makeDiffNode(DIFF_TYPE_CHANGED, $key, $modifiedData->get($key), $originalData->get($key));
        }

        return makeDiffNode(DIFF_TYPE_SAME, $key, $originalData->get($key));
    });
}

/**
 * @param string $type
 * @param string $key
 * @param mixed $value
 * @param mixed $previousValue
 * @return array
 */
function makeDiffNode($type, $key, $value, $previousValue = null)
{
    return compact('type', 'key', 'value', 'previousValue');
}

function getNodeType(array $node): string
{
    return $node['type'];
}

function getNodeKey(array $node): string
{
    return $node['key'];
}

/**
 * @return mixed
 */
function getNodeValue(array $node)
{
    return $node['value'];
}

/**
 * @param array $node
 * @return mixed
 */
function getNodePreviousValue(array $node)
{
    return $node['previousValue'];
}
