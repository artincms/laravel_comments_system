<?php

namespace ArtinCMS\LCS\Model;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function user()
    {
        return $this->belongsTo(config('laravel_comments_system.user_model'), 'user_id');
    }
}
