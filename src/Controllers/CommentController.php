<?php

namespace ArtinCMS\LCS\Controllers;

use App\Article;
use ArtinCMS\LCS\Model\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CommentController extends Controller
{
    public function getdata(Request $request)
    {
        $model = $request->model;
        $id = $request->id;
        $pid_key = $request->pid_key;
        $model = $model::find($id);
        $data['name'] = 'main';
        $data['comment'] = 'main';
        $data['id'] = 0;
        $data['parent_id'] = 0;
        $data['target_id'] = $request->id;
        $data['target_type'] = $request->model;
        $data['created_at'] = '';
        //$data['lang'] ='"'.app()->getLocale() .'"';
        $data['lang'] =(string)app()->getLocale() ;
        $data['user_id'] = LCS_getUserId();
        if ($data['user_id'] == 0 && config('laravel_comments_system.autoPublish') == false)
        {
            $data['canComment']=false ;
        }
        else
        {
            $data['canComment']=true ;
        }
        foreach ($model->comments->toArray() as $value)
        {
            $data['data_array'][$value['id'] ]=$value;
        }
        if (config('laravel_comments_system.autoPublish'))
        {
            $data['children'] = LCS_BuildTree($model->comments->toArray(), $pid_key, false, false, 0);
        }
        else
        {
            $data['children'] = LCS_BuildTree($model->comments->where('approved', '=', 1)->toArray(), $pid_key, false, false, 0);

        }
        return json_encode($data);
    }

    public function saveComment(Request $request)
    {
        if (LCS_getUserId()== 0 && config('laravel_comments_system.autoPublish') == false)
        {
            $result['success'] = false;
        }
        else
        {
            $comment = new Comment;
            $comment->user_id = LCS_getUserId();
            $comment->target_id = $request->target_id;
            $comment->target_type = $request->target_type;
            $comment->quote_id = $request->quote_id;
            if ($comment->user_id == 0)
            {
                $comment->name = $request->name;
                $comment->email = $request->email;
            }
            else
            {
                if (\Auth::user()->name)
                {
                    $comment->name = \Auth::user()->name;
                }
                else
                {
                    $comment->name = 'No name';
                }
                if (\Auth::user()->email)
                {
                    $comment->email = \Auth::user()->email;
                }
                else
                {
                    $comment->email = 'not define';
                }
            }

            $comment->comment = $request->comment;
            $comment->parent_id = $request->parent_id;
            $comment->created_by = LCS_getUserId();
            $comment->save();
            $result['success'] = true;
            $result['autoPublish'] = config('laravel_comments_system.autoPublish');
            return response()->json($result);
        }

    }
}
