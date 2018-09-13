<?php

namespace ArtinCMS\LCS\Controllers;

use App\Article;
use App\User;
use ArtinCMS\LCS\Model\CommentItem;
use ArtinCMS\LMM\Models\Morph;
use Illuminate\Support\Facades\Auth;
use Validator;
use ArtinCMS\LCS\Model\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\LaravelCommentSystem;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Exceptions\HttpResponseException;

class CommentController extends Controller
{
    use LaravelCommentSystem;

    public function getdata(Request $request)
    {
        $model = $request->model;
        $id = LCS_GetDecodeId($request->id);
        $pid_key = $request->pid_key;
        $model = $model::find($id);
        $data['name'] = 'main';
        $data['comment'] = 'main';
        $data['id'] = 0;
        $data['encode_id'] = 0;
        $data['encode_parent_id'] = 0;
        $data['parent_id'] = 0;
        $data['target_id'] = $request->id;
        $data['encode_target_id'] = $request->id;
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
        $comments = $model->comments ;
        if($comments)
        {
            foreach ($comments->toArray() as $value)
            {
                $data['data_array'][$value['encode_id']] = $value;
            }
            if (config('laravel_comments_system.autoPublish'))
            {
                $data['children'] = LCS_BuildTree($comments->toArray(), $pid_key, false, false, LCS_getEncodeId(0),'encode_id');
            }
            else
            {
                $data['children'] = LCS_BuildTree($comments>where('approved', '=', 1)->toArray(), $pid_key, false, false, 0,'encode_id');

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
        //dd($request->all(),LFM_GetDecodeId($request->parent_id),LFM_GetDecodeId($request->target_id));
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
                $comment->target_id = LCS_GetDecodeId($request->target_id);
                $comment->target_type = $request->target_type;
                $comment->quote_id = LCS_GetDecodeId($request->quote_id);
                if ($comment->user_id == 0)
                {
                    $comment->name = $request->name;
                    $comment->email = $request->email;
                }


                $comment->comment = $request->comment;
                $comment->parent_id =  LCS_GetDecodeId($request->parent_id);
                $comment->created_by = LCS_getUserId();
                $comment->save();
                $result['success'] = true;
                $result['message'] = __('lcs_fronted.tanks_message');
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
        $query = Comment::with('user');
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
            ->addColumn('title', function ($data) {
                return $data->title;
            })
            ->addColumn('url', function ($data) {
                return $data->url;
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
        $item = Comment::find(LFM_GetDecodeId($request->item_id));
        if ($request->is_active == "true")
        {
            $item->approved = "1";
            $res['message'] = ' مجموعه فعال گردید';
        }
        else
        {
            $item->approved = "0";
            $res['message'] = 'مجموعه غیر فعال شد';
        }
        $item->save();
        $res['success'] = true;
        $res['title'] = 'وضعیت مجموعه تغییر پیدا کرد .';
        return $res;
    }

    function showSetting()
    {
        $morphs = Morph::select('id','name As text')->get();
        return view('laravel_comments_system::backend.setting',compact('morphs'));
    }

    public function createCommentItems(Request $request)
    {
        $comment = new CommentItem ;
        $comment->title = $request->title ;
        $comment->morphable_id = $request->morph_id ;
        if (auth()->check())
        {
            $auth = auth()->id();
        }
        else
        {
            $auth = 0;

        }
        $comment->created_by =$auth;
        $comment->save() ;
        $res =
            [
                'success' => true,
                'title' => "ثبت آیتم",
                'message' => 'آیتم با موفقیت ثبت شد.'
            ];

        return $res;
    }

    public function getCommentItemDatatable(Request $request)
    {
        $item = CommentItem::with('user');
        return DataTables::eloquent($item)
            ->editColumn('id', function ($data) {
                return LCS_getEncodeId($data->id);
            })
            ->make(true);

    }

    public function getEditSettingsForm(Request $request)
    {
        $settings = CommentItem::find(LCS_GetDecodeId($request->item_id)) ;
        $morphs = Morph::select('id','name As text')->get();
        $settings_form = view('laravel_comments_system::backend.view.edit_setting', compact('settings','morphs'))->render();
        $res =
            [
                'success' => true,
                'setting_edit_view' => $settings_form
            ];
        throw new HttpResponseException(
            response()
                ->json($res, 200)
                ->withHeaders(['Content-Type' => 'text/plain', 'charset' => 'utf-8'])
        );
    }

    public function editSetting(Request $request)
    {
        $comment = CommentItem::find($request->item_id);
        $comment->title = $request->title ;
        $comment->morphable_id = $request->morph_id ;
        if (auth()->check())
        {
            $auth = auth()->id();
        }
        else
        {
            $auth = 0;

        }
        $comment->created_by =$auth;
        $comment->save() ;
        $res =
            [
                'success' => true,
                'title' => "ویرایش آیتم",
                'message' => 'آیتم با موفقیت ثبت شد.'
            ];

        return $res;
    }

    public function changeSettingStatus(Request $request)
    {
        $item = CommentItem::find(LFM_GetDecodeId($request->item_id));
        if ($request->is_active == "true")
        {
            $item->is_active = "1";
            $res['message'] = ' آیتم فعال گردید';
        }
        else
        {
            $item->is_active = "0";
            $res['message'] = 'آیتم غیر فعال شد';
        }
        $item->save();
        $res['success'] = true;
        $res['title'] = 'وضعیت آیتم تغییر پیدا کرد .';

        return $res;
    }

    public function trashSetting(Request $request)
    {
        $item = CommentItem::find(LFM_GetDecodeId($request->item_id));
        $item->delete();

        $res =
            [
                'success' => true,
                'title' => "حذف آیتم",
                'message' => 'آیتم با موفقیت حذف شد.'
            ];

        throw new HttpResponseException(
            response()
                ->json($res, 200)
                ->withHeaders(['Content-Type' => 'text/plain', 'charset' => 'utf-8'])
        );
    }
}
