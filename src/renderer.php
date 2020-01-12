<?php

namespace DiffTool\renderer;

function renderDiffTree($diffTree, $renderFormat)
{
    $renderFunction = "DiffTool\\renderers\\{$renderFormat}\\renderDiffTree";
    // print_r($renderFunction);exit;
    return $renderFunction($diffTree);
}
