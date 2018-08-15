<?php

function LCS_BuildTree($flat_array, $pidKey, $openNodes = true, $selectedNode = false, $parent = 0, $idKey = 'id', $children_key = 'children')
{
    $grouped = array();
    foreach ($flat_array as $sub)
    {
        $sub['name'] = LCS_getUserData($sub)['name'];
        $sub['text'] = $sub['name'];
        if ((int)$sub['encode_id'] == (int)$selectedNode)
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
                $sibling{$children_key} = $fnBuilder($grouped[$id]);
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

function LCS_CreateCommentsTemplate($obj_model, $pid_key)
{
    $data = LCS_BuildTree($obj_model->comments->toArray(), $pid_key, false, false, 1);
    $data = json_encode($data);
    $result = view('laravel_comments_system.frontend.index', compact('data'));
    return $result;
}

function LCS_getUserId()
{
    if (auth()->check())
    {
        $user_id = auth()->id();
    }
    else
    {
        $user_id = 0;
    }
    return $user_id;
}

function LCS_getUserData($comment)
{
    $user_id=$comment['user_id'];
    $user = config('laravel_comments_system.userModel')::find($user_id);
    if ($user)
    {
        $user['name'] = $user->name;
        $user['email'] = $user->email;
        $user['profile_pic'] = 'Public';
    }
    else
    {
        $user['name'] =$comment['name'];
        $user['email'] = $comment['email'];
        $user['profile_pic'] = 'Public';
    }
    return $user;
}

function LCS_createBackendCommentHtml()
{
    $src = route('LCS.indexCommentBackend');
    $html = '<iframe style="width:100%;height: calc(100vh - 51px);    max-height: calc(100vh - 50px);    border: none;" id="modalIframeShowReplyComment" src="'.$src.'"></iframe>';
    return $html ;

}

function LCS_getEncodeId($id)
{
    if ($id < 0)
    {
        return $id;
    }
    else
    {
        $hashids = new \Hashids\Hashids(md5('sadeghi'));

        return $hashids->encode($id);
    }
}

function LCS_GetDecodeId($id, $route = false)
{
    $my_routes = [
        'LCS.indexCommentBackend',
        'LCS.replyToComment',
    ];

    if ((int)$id < 0)
    {
        return (int)$id;
    }
    else
    {
        $hashids = new \Hashids\Hashids(md5('sadeghi'));
        if ($route)
        {
            if (in_array($route->getName(), $my_routes))
            {
                if ($hashids->decode($id) != [])
                {
                    if(isset($hashids->decode($id)[0]))
                    {
                        return $hashids->decode($id)[0];
                    }
                    else
                    {
                        return $id;
                    }
                }
                else
                {
                    return $id;
                }
            }
            else
            {
                return $id;
            }
        }
        else
        {
            if(isset($hashids->decode($id)[0]))
            {
                return $hashids->decode($id)[0];
            }
            else
            {
                return $id ;
            }
        }
    }
}
