<?php

namespace ArtinCMS\LCS\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommentItemValue extends Model
{
    protected $table = 'lcm_comment_item_result';
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo(config('laravel_comments_system.user_model'), 'user_id');
    }

}
