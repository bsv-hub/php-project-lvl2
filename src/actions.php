<?php

namespace DiffTool\actions;

use function DiffTool\parsers\parse;
use function DiffTool\parsers\getParsedTypeForExtension;
use function DiffTool\diffTree\makeDiffTree;
use function DiffTool\renderers\renderDiffTree;

function getRenderedFilesDiffText($pathToOriginalFile, $pathToChangedFile, $renderFormat = 'plain')
{
    $contentsOfOriginalFile = file_get_contents($pathToOriginalFile);
    $contentsOfChangedFile = file_get_contents($pathToChangedFile);
    $extensionOfOriginalFile = pathinfo($pathToOriginalFile, PATHINFO_EXTENSION);
    $extensionOfChangedFile = pathinfo($pathToChangedFile, PATHINFO_EXTENSION);
    $originalData = parse($contentsOfOriginalFile, getParsedTypeForExtension($extensionOfOriginalFile));
    $changedData = parse($contentsOfChangedFile, getParsedTypeForExtension($extensionOfChangedFile));
    $diffTree = makeDiffTree($originalData, $changedData);
    return renderDiffTree($diffTree, $renderFormat);
}
