<?php

namespace ArtinCMS\LCS\Controllers;

use App\Article;
use App\User;
use Datatables;
use Illuminate\Support\Facades\Auth;
use Validator;
use ArtinCMS\LCS\Model\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\LaravelCommentSystem;


class CommentController extends Controller
{
    use LaravelCommentSystem;

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
        $data['created_at'] = 'default';
        //$data['lang'] ='"'.app()->getLocale() .'"';
        $data['lang'] = (string)app()->getLocale();
        $data['user_id'] = LCS_getUserId();
        if ($data['user_id'] == 0 && config('laravel_comments_system.guestCanComments') == false)
        {
            $data['canComment'] = false;
        }
        else
        {
            $data['canComment'] = true;
        }
        if($model->comments)
        {
            foreach ($model->comments->toArray() as $value)
            {
                $data['data_array'][$value['id']] = $value;
            }
            if (config('laravel_comments_system.autoPublish'))
            {
                $data['children'] = LCS_BuildTree($model->comments->toArray(), $pid_key, false, false, 0);
            }
            else
            {
                $data['children'] = LCS_BuildTree($model->comments->where('approved', '=', 1)->toArray(), $pid_key, false, false, 0);

            }
        }
        else
        {
            $data['data_array'] =[];
            $data['children']=[];
        }
        return json_encode($data);
    }

    public function saveComment(Request $request)
    {
        if (LCS_getUserId() == 0 && config('laravel_comments_system.guestCanComments') == false)
        {
            $result['success'] = false;
            return json_encode($result);
        }
        else
        {
            $validator = Validator::make($request->all(), [
                'comment' => 'required',
            ]);

            if ($validator->fails())
            {
                $result['error'] = $validator->errors();
                $result['success'] = false;
                return json_encode($result);
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

    public function showCommentFronted ($id,$target_id)
    {
        return view('laravel_comments_system::frontend.showCommentFronted', compact('id','target_id'));
    }

    public function indexCommentBackend()
    {
        return view('laravel_comments_system::backend.index');
    }

    public function getCommentDataTable()
    {
        $query = Comment::with('user')->select('comments.*');
        return datatables()->eloquent($query)
            ->editColumn('id', function ($data) {
                return LCS_getEncodeId($data->id);
            })
            ->editColumn('user', function ($data) {
                if(!isset($data->user ))
                {
                    return ['name' => null ,'email'=>null];
                }
                else
                {
                    return $data->user->toArray() ;
                }
            })
            ->addColumn('url', function ($data) {
                $function = config('laravel_comments_system.Trait.Method');
                $res = $this->$function($data)['url'];
                return $res;
            })
            ->addColumn('title', function ($data) {
                $function = config('laravel_comments_system.Trait.Method');
                $res = $this->$function($data)['text'];
                return $res;
            })
            ->toJson();
    }

    public function replyToComment($commentId)
    {
        $commentId = LCS_GetDecodeId($commentId);
        $comment = Comment::find($commentId);
        $commentId=LCS_getEncodeId($comment->id);
        return view('laravel_comments_system::backend.replyToComment', compact('comment','commentId'));
    }

    public function storeReplyToComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
            'name' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            $result['error'] = $validator->errors();
            $result['success'] = false;
            return json_encode($result);
        } else {
            $id=LCS_GetDecodeId($request->id) ;
            $comment = Comment::find($id);
            $comment->created_by =LCS_getUserId();
            $comment->name = $request->name;
            $comment->approved = $request->approved;
            $comment->email = $request->email;
            $comment->comment = $request->comment;
            if ($request->reply)
            {
                $reply = new Comment ;
                $reply->created_by =LCS_getUserId();
                $reply->user_id =LCS_getUserId();
                if (Auth::user())
                {
                    if (\Auth::user()->name)
                    {
                        $reply->name = \Auth::user()->name;
                    }
                    else
                    {
                        $reply->name = 'No name';
                    }
                    if (\Auth::user()->email)
                    {
                        $reply->email = \Auth::user()->email;
                    }
                    else
                    {
                        $reply->email = 'not define';
                    }
                }
                else
                {
                    $reply->name = 'No name';
                    $reply->email = 'not define';
                }

                $reply->comment =$request->reply;
                $reply->target_id =$comment->target_id;
                $reply->target_type =$comment->target_type;
                $reply->approved = 1;
                $reply->parent_id = $id;
                $reply->save() ;
                $comment->approved = 1 ;
            }
            $comment->save();
            $result['message'][] = 'you commented successfully';
            $result['success'] = true;
            return json_encode($result);
        }
    }

    function trashComment(Request $request)
    {
        $id = LCS_GetDecodeId($request->id);
        Comment::destroy($id);
        $result['message'][] = __('commentBackend.operation_is_success');
        $result['success'] = true;
        return json_encode($result);
    }

    function approveComment(Request $request)
    {
      foreach ($request->id as $item_id)
      {
          $id = LCS_GetDecodeId($item_id);
          $comment = Comment::find($id);
          if ($request->value == 0)
          {
              $comment->approved = 1 ;
          }
          else
          {
              $comment->approved = 0 ;
          }
          $comment->save();
          $result['message'][] = __('commentBackend.operation_is_success');
          $result['success'] = true;
      }
        return json_encode($result);
    }


}
