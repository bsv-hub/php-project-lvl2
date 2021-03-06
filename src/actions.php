<?php

namespace DiffTool\actions;

use function DiffTool\parsers\parse;
use function DiffTool\parsers\getParsedTypeForExtension;
use function DiffTool\diffTree\makeDiffTree;
use function DiffTool\renderers\renderDiffTree;
use function Safe\file_get_contents;

function getFilesDiffText(
    string $pathToOriginalFile,
    string $pathToModifiedFile,
    string $renderFormat = 'plain'
): string {
    $contentsOfOriginalFile = file_get_contents($pathToOriginalFile);
    $contentsOfModifiedFile = file_get_contents($pathToModifiedFile);
    $extensionOfOriginalFile = pathinfo($pathToOriginalFile, PATHINFO_EXTENSION);
    $extensionOfModifiedFile = pathinfo($pathToModifiedFile, PATHINFO_EXTENSION);
    $originalData = parse($contentsOfOriginalFile, getParsedTypeForExtension($extensionOfOriginalFile));
    $modifiedData = parse($contentsOfModifiedFile, getParsedTypeForExtension($extensionOfModifiedFile));
    $diffTree = makeDiffTree($originalData, $modifiedData);
    return renderDiffTree($diffTree, $renderFormat);
}
