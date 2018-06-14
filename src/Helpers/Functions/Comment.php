<?php

function LCS_BuildTree($flat_array, $pidKey, $openNodes = true, $selectedNode = false, $parent = 0, $idKey = 'id', $children_key = 'children')
{
    $grouped = array();
    foreach ($flat_array as $sub)
    {
        $sub['text'] = $sub['name'];
        $sub['a_attr'] = ['class' => 'link_to_category jstree_a_tag', 'data-id' => LFM_getEncodeId($sub['id'])];
        if ((int)$sub['id'] == (int)$selectedNode)
        {
            $sub['state'] = ['selected' => true, 'opened' => true];

        }
        $grouped[$sub[$pidKey]][] = $sub;

    }
    $fnBuilder = function ($siblings) use (&$fnBuilder, $grouped, $idKey, $children_key) {
        foreach ($siblings as $k => $sibling)
        {
            $id = $sibling[$idKey];
            if (isset($grouped[$id]))
            {
                $sibling[$children_key] = $fnBuilder($grouped[$id]);
            }
            $siblings[$k] = $sibling;
        }
        return $siblings;
    };
    if (isset($grouped[$parent]))
    {
        $tree = $fnBuilder($grouped[$parent]);
    }
    else
    {
        $tree = [];
    }
    return $tree;
}

function LCS_CreateCommentsTemplate($obj_model,$pid_key)
{
    $data = LCS_BuildTree($obj_model->comments->toArray(), $pid_key, false, false, 1);
    $data = json_encode($data[1]);
    $result =  view('laravel_comments_system.frontend.index',compact('data'));
    return $result ;
}