<?php

namespace ArtinCMS\LCS\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommentItem extends Model
{
    protected $table = 'lcm_comment_items';
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo(config('laravel_comments_system.userModel'), 'user_id');
    }

}
