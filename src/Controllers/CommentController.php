<?php

namespace ArtinCMS\LCS\Controllers;

use App\Article;
use App\User;
use ArtinCMS\LCS\Model\CommentItem;
use ArtinCMS\LCS\Model\CommentItemValue;
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
        $morph = Morph::where('dev_name', $model)->first();
        $jalali_date = $request->jalali_data ;
        if ($morph)
        {
            $target_type = $morph->model_name;
        }
        else
        {
            $target_type = $model;
        }
        $id = LCS_GetDecodeId($request->id);
        $pid_key = $request->pid_key;
        $model = $target_type::find($id);
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
        if ($data['user_id'] == 0 && config('laravel_comments_system.guest_can_comments') == false)
        {
            $data['canComment'] = false;
        }
        else
        {
            $data['canComment'] = true;
        }
        if ($model)
        {
            if (isset($model->comments))
            {
                $comments = $model->comments;
                foreach ($comments->toArray() as $value)
                {
                    $data['data_array'][ $value['encode_id'] ] = $value;
                }
                if (config('laravel_comments_system.auto_publish'))
                {
                    $data['children'] = LCS_BuildTree($comments->toArray(), $pid_key, false, false, LCS_getEncodeId(0), 'encode_id','children',$jalali_date);
                }
                else
                {
                    $data['children'] = LCS_BuildTree($comments -> where('approved', '=', 1)->toArray(), $pid_key, false, false,  LCS_getEncodeId(0), 'encode_id','children',$jalali_date);

                }
            }
            else
            {
                $data['data_array'] = [];
                $data['children'] = [];
            }
        }
        else
        {
            $data['data_array'] = [];
            $data['children'] = [];
        }
        if (config('laravel_comments_system.show_comment_item'))
        {
            $items = CommentItem::with('commentValues')->where('morphable_id', $morph->id)->get();
            $result = [];
            $all_perc = 0;
            $count = 0;
            $item_value_ids = [] ;
            foreach ($items as $item)
            {
                $avg = 0;

                    foreach ($item->commentValues as $value)
                    {
                        if(!in_array($value->comment_id,$item_value_ids))
                        {
                            $item_value_ids[] = $value->comment_id ;
                        }

                        $avg += $value->comment_item_value;
                        $count++;
                    }
                    $res = ['id' => $item->id, 'title' => $item->title];
                    if(count($item->commentValues) > 0)
                    {
                        $perc = ($avg / count($item->commentValues)) * 20;
                    }
                    else
                    {
                        $perc = 0 ;
                    }
                    $all_perc += $perc;
                    $res['avg'] = round($perc);
                    $result[] = $res;
                    $item_ids[] = $item->id ;
            }
        }
        else
        {
            $items = [];
            $result = [];
        }
        if ($all_perc != 0)
        {
            $data['all_avg'] = round($all_perc / count($result));
        }
        else
        {
            $data['all_avg'] = false;
        }
        $data['items'] = $items;
        $data['login_url'] = config('laravel_comments_system.login_url');
        $data['count'] = count($item_value_ids);
        $data['result'] = $result;
        if ($data['canComment'])
        {
            if (auth()->check())
            {
                $auth = auth()->id();
            }
            else
            {
                $auth = 0;

            }
            $user_can_comment = Comment::where([
                ['target_id', LCS_GetDecodeId($request->id)],
                ['target_type', $target_type],
                ['user_id', $auth]
            ]);
            $user_can_comments=$user_can_comment->get() ;
            if (count($user_can_comments) > 0)
            {
                $array = [];
                foreach ($user_can_comments as $user_comment)
                {
                    $array[] = LCS_getEncodeId($user_comment->parent_id);
                    $array[] = LCS_getEncodeId($user_comment->id);
                }
                $data['user_comment'] = $array;
                if(count($user_can_comment->where('parent_id',0)->get()) > 0)
                {
                    $data['user_can_comment'] = false;
                }
                else
                {
                    $data['user_can_comment'] = true;
                }
            }
            else
            {
                $data['user_comment'] = false;
                $data['user_can_comment'] = true;
            }
        }
        else
        {
            $data['user_can_comment'] = false;
            $data['user_comment'] = false;
        }

        return json_encode($data);
    }

    public function saveComment(Request $request)
    {
        if (LCS_getUserId() == 0 && config('laravel_comments_system.guest_can_comments') == false)
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
                $morph = Morph::where('dev_name', $request->target_type)->first();
                if ($morph)
                {
                    $model = $morph->model_name;
                }
                else
                {
                    $model = $request->target_type;
                }
                $comment = new Comment;
                $comment->user_id = LCS_getUserId();
                $comment->target_id = LCS_GetDecodeId($request->target_id);
                $comment->target_type = $model;
                $comment->quote_id = LCS_GetDecodeId($request->quote_id);
                if ($comment->user_id == 0)
                {
                    $comment->name = $request->name;
                    $comment->email = $request->email;
                }


                $comment->comment = $request->comment;
                $comment->parent_id = LCS_GetDecodeId($request->parent_id);
                $comment->created_by = LCS_getUserId();
                $comment->save();
                foreach ($request->items as $key => $value)
                {
                    if ($value)
                    {
                        $res = new CommentItemValue;
                        $res->comment_id = $comment->id;
                        $res->comment_item_id = $key;
                        $res->comment_item_value = $value;
                        if (auth()->check())
                        {
                            $auth = auth()->id();
                        }
                        else
                        {
                            $auth = 0;

                        }
                        $res->created_by = $auth;
                        $res->save();
                    }
                }
                $result['success'] = true;
                $result['message'] = __('lcs_fronted.tanks_message');
                $result['auto_publish'] = config('laravel_comments_system.auto_publish');

                return response()->json($result);
            }

        }

    }

    public function showCommentFronted($id, $target_id)
    {
        return view('laravel_comments_system::frontend.showCommentFronted', compact('id', 'target_id'));
    }

    public function indexCommentBackend()
    {
        $morphs = Morph::select('id', 'name As text')->get();

        return view('laravel_comments_system::backend.index', compact('morphs'));
    }

    public function getCommentDataTable()
    {
        $query = Comment::with('user');

        return datatables()->eloquent($query)
            ->editColumn('id', function ($data) {
                return LCS_getEncodeId($data->id);
            })
            ->editColumn('name', function ($data) {
                if($data->name)
                {
                    return $data->name ;
                }
                else
                {
                    $user =config('laravel_comments_system.user_data')($data->user_id) ;
                    if($user)
                    {
                        if (isset($user['username']))
                        {
                            return $user['username'] ;
                        }
                        else
                        {
                            return '' ;
                        }
                    }
                    else
                    {
                        return '' ;
                    }
                }
            })
            ->editColumn('email', function ($data) {
                if($data->email)
                {
                    return $data->email ;
                }
                else
                {
                    $user =config('laravel_comments_system.user_data')($data->user_id) ;
                    if($user)
                    {
                        if (isset($user['email']))
                        {
                            return $user['email'] ;
                        }
                        else
                        {
                            return '' ;
                        }
                    }
                    else
                    {
                        return '' ;
                    }
                }
            })
            ->addColumn('morph_name', function ($data) {
                return $data->morph_name;
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
        $commentId = LCS_getEncodeId($comment->id);

        return view('laravel_comments_system::backend.replyToComment', compact('comment', 'commentId'));
    }

    public function storeReplyToComment(Request $request)
    {
        if ($request->user_id == 0)
        {
            $validator = Validator::make($request->all(), [
                'comment' => 'required',
                'name'    => 'required',
                'email'   => 'required',
            ]);
            $email = $request->email;
            $name = $request->name;
        }
        else
        {
            $validator = Validator::make($request->all(), [
                'comment' => 'required',
            ]);
            $email = null;
            $name = null;
        }

        if ($validator->fails())
        {
            $result['error'] = $validator->errors();
            $result['success'] = false;

            return json_encode($result);
        }
        else
        {
            $id = LCS_GetDecodeId($request->encode_id);
            $comment = Comment::find($id);
            $comment->created_by = LCS_getUserId();
            $comment->approved = $request->approved;
            $comment->name = $name;
            $comment->email = $email;
            $comment->comment = $request->comment;
            if ($request->reply)
            {
                $reply = new Comment;
                $reply->created_by = LCS_getUserId();
                $reply->user_id = LCS_getUserId();
                if (Auth::user())
                {
                    if (\Auth::user()->name)
                    {
                        $reply->name = \Auth::user()->name;
                    }
                    if (\Auth::user()->email)
                    {
                        $reply->email = \Auth::user()->email;
                    }
                }
                $reply->comment = $request->reply;
                $reply->target_id = $comment->target_id;
                $reply->target_type = $comment->target_type;
                $reply->approved = 1;
                $reply->parent_id = $id;
                $reply->save();
                $comment->approved = 1;
            }
            $comment->save();
            if(config('laravel_comments_system.user_data'))
            {
                $func = config('laravel_comments_system.user_data')($comment->user_id) ;
                if(isset($func->name) && isset($func->email))
                {
                    $info =
                        [
                            'email'             => $func->email,
                            'comment'             => $func->comment,
                        ];
//                    Mail::to($func->email)->queue();
                }
            }
            $result['message'] = 'پیغام شما با موفقیت ثبت گردید';
            $result['title'] = 'ثبت نظر';
            $result['success'] = true;

            return json_encode($result);
        }
    }

    function trashComment(Request $request)
    {
        $item = Comment::find(LFM_GetDecodeId($request->item_id));
        if ($item)
        {
            $item->delete();
            $res =
                [
                    'success' => true,
                    'title'   => "حذف آیتم",
                    'message' => 'آیتم با موفقیت حذف شد.'
                ];

        }
        else
        {
            $res =
                [
                    'success' => false,
                    'title'   => "حذف آیتم",
                    'message' => 'خطا در حذف آیتم'
                ];
        }


        throw new HttpResponseException(
            response()
                ->json($res, 200)
                ->withHeaders(['Content-Type' => 'text/plain', 'charset' => 'utf-8'])
        );
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
        $morphs = Morph::select('id', 'name As text')->get();

        return view('laravel_comments_system::backend.setting', compact('morphs'));
    }

    public function createCommentItems(Request $request)
    {
        $comment = new CommentItem;
        $comment->title = $request->title;
        $comment->morphable_id = $request->morph_id;
        if (auth()->check())
        {
            $auth = auth()->id();
        }
        else
        {
            $auth = 0;

        }
        $comment->created_by = $auth;
        $comment->save();
        $res =
            [
                'success' => true,
                'title'   => "ثبت آیتم",
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
            ->addColumn('morph', function ($data) {
                if (isset($data->morph->name))
                {
                    return $data->morph->name;
                }
                else
                {
                    return '' ;
                }
            })
            ->make(true);

    }

    public function getEditSettingsForm(Request $request)
    {
        $settings = CommentItem::find(LCS_GetDecodeId($request->item_id));
        $morphs = Morph::select('id', 'name As text')->get();
        $settings_form = view('laravel_comments_system::backend.view.edit_setting', compact('settings', 'morphs'))->render();
        $res =
            [
                'success'           => true,
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
        $comment->title = $request->title;
        $comment->morphable_id = $request->morph_id;
        if (auth()->check())
        {
            $auth = auth()->id();
        }
        else
        {
            $auth = 0;

        }
        $comment->created_by = $auth;
        $comment->save();
        $res =
            [
                'success' => true,
                'title'   => "ویرایش آیتم",
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
                'title'   => "حذف آیتم",
                'message' => 'آیتم با موفقیت حذف شد.'
            ];

        throw new HttpResponseException(
            response()
                ->json($res, 200)
                ->withHeaders(['Content-Type' => 'text/plain', 'charset' => 'utf-8'])
        );
    }

    public function getReplyToCommentForm(Request $request)
    {
        $comment = Comment::find(LCS_GetDecodeId($request->item_id));
        $comment->encode_id = LCS_getEncodeId($comment->id);
        $reply_form = view('laravel_comments_system::backend.view.replyToComment', compact('comment'))->render();
        $res =
            [
                'success'    => true,
                'reply_view' => $reply_form
            ];
        throw new HttpResponseException(
            response()
                ->json($res, 200)
                ->withHeaders(['Content-Type' => 'text/plain', 'charset' => 'utf-8'])
        );
    }
}