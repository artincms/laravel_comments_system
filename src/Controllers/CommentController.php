<?php

namespace ArtinCMS\LCS\Controllers;

use App\Article;
use ArtinCMS\LCS\Model\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CommentController extends Controller
{
   public function getdata (Request $request)
   {
       $model = $request->model;
       $id=$request->id;
       $pid_key=$request->pid_key ;
       $model =$model::find($id) ;
       if (config('laravel_comments_system.autoPublish'))
       {
           $data =  LCS_BuildTree($model->comments->toArray(), $pid_key, false, false, 0);
       }
       else
       {
           $child =  LCS_BuildTree($model->comments->where('approved','=',1)->toArray(), $pid_key, false, false, 0);
           $data['name']='main';
           $data['comment']='main';
           $data['id']= 0;
           $data['parent_id']= 0;
           $data['target_id']= $request->id;
           $data['target_type']=  $request->model;
           $data['created_at']= '';
           $data['children']=$child;
       }
       return json_encode($data) ;
   }

   public function saveComment(Request $request)
   {
       $comment = new Comment ;
       $comment->target_id= $request->target_id;
       $comment->target_type= $request->target_type;
       $comment->name= $request->name;
       $comment->email= $request->email;
       $comment->comment= $request->comment;
       $comment->parent_id= $request->parent_id;
       $comment->save();
       $result['success'] = true ;
       $result['autoPublish'] = config('laravel_comments_system.autoPublish') ;
       return response()->json($result);
   }
}
