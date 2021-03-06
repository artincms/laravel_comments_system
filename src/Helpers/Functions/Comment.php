<?php

function LCS_BuildTree($flat_array, $openNodes = true, $selectedNode = false, $parent = 0, $idKey = 'id', $children_key = 'children',$jalali_date=true)
{
    $grouped = array();
    foreach ($flat_array as $sub)
    {
        $sub['name'] = LCS_getUserData($sub)['name'];
        if($jalali_date)
        {
            $sub['created_at'] =   $date = LCS_Date_GtoJ($sub['created_at'],'Y-m-d');;
        }
        $sub['text'] = $sub['name'];
        if ((int)$sub['encode_id'] == (int)$selectedNode)
        {
            $sub['state'] = ['selected' => true, 'opened' => true];

        }
        $grouped[$sub['encode_parent_id']][] = $sub;

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
    $user = config('laravel_comments_system.user_model')::find($user_id);
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

function LCS_GetUserInformation($user_id)
{
    $user = \Illuminate\Foundation\Auth\User::find($user_id);
    return [
        'username' => $user->username,
        'email' => $user->email,
    ] ;
}

function LCS_Date_GtoJ($GDate = null, $Format = "Y/m/d-H:i", $convert = true)
{
    if ($GDate == '-0001-11-30 00:00:00' || $GDate == null)
    {
        return '--/--/----';
    }
    $date = new ArtinCMS\LCS\Helpers\Classes\jDateTime($convert, true, 'Asia/Tehran');
    $time = is_numeric($GDate) ? strtotime(date('Y-m-d H:i:s', $GDate)) : strtotime($GDate);

    return $date->date($Format, $time);

}
function LCS_Date_JtoG($jDate, $delimiter = '/', $to_string = false, $with_time = false, $input_format = 'Y/m/d H:i:s')
{
    $jDate = ConvertNumbersFatoEn($jDate);
    $parseDateTime = ArtinCMS\LCS\Helpers\Classes\jDateTime::parseFromFormat($input_format, $jDate);
    $r = ArtinCMS\LCS\Helpers\Classes\jDateTime::toGregorian($parseDateTime['year'], $parseDateTime['month'], $parseDateTime['day']);
    if ($to_string)
    {
        if ($with_time)
        {
            $r = $r[0] . $delimiter . $r[1] . $delimiter . $r[2] . ' ' . $parseDateTime['hour'] . ':' . $parseDateTime['minute'] . ':' . $parseDateTime['second'];
        }
        else
        {
            $r = $r[0] . $delimiter . $r[1] . $delimiter . $r[2];
        }
    }

    return ($r);
}
