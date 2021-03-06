<?php

namespace ArtinCMS\LCS\Model;

use ArtinCMS\LMM\Models\Morph;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    public function user()
    {
        return $this->belongsTo(config('laravel_comments_system.user_model'), 'user_id');
    }
    public function getMorphNameAttribute()
    {
        $target = Morph::where('model_name',$this->target_type)->first();
        return $target->name ;
    }
    public function getTitleAttribute()
    {
        $target = Morph::where('model_name',$this->target_type)->first();
        if($target)
        {
            $column_name = $target->target_column_name ;
            $model = $this->target_type::where('id',$this->target_id)->first();
            if($model)
            {
                return $model->$column_name;
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

    public function commentValues()
    {
        return $this->hasMany('ArtinCMS\LCS\Model\CommentItemValue','comment_id','id');
    }
}
