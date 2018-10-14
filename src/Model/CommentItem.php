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
        return $this->belongsTo(config('laravel_comments_system.user_model'), 'user_id');
    }
    public function commentValues()
    {
        return $this->hasMany('ArtinCMS\LCS\Model\CommentItemValue','comment_item_id','id');
    }
    public function morph()
    {
        return $this->belongsTo('ArtinCMS\LMM\Models\Morph', 'morphable_id');
    }

}
