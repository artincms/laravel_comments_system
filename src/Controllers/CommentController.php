<?php

namespace ArtinCMS\LCS\Controllers;

use App\Article;
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
       $data = LCS_BuildTree($model->comments->toArray(), $pid_key, false, false, 1);
       return json_encode($data[1]) ;
   }
}
