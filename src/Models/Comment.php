<?php

namespace ArtinCMS\LCS\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $hidden = ['id','parent_id','target_id'];
    protected $appends = ['encode_id','encode_parent_id','encode_target_id','encode_quote_id'];

    public function getEncodeIdAttribute()
    {
        return LCS_getEncodeId($this->id);
    }

    public function getEncodeQuoteIdAttribute()
    {
        return LCS_getEncodeId($this->quote_id);
    }

    public function getEncodeParentIdAttribute()
    {
        return LCS_getEncodeId($this->parent_id);
    }

    public function getEncodeTargetIdAttribute()
    {
        return LCS_getEncodeId($this->target_id);
    }

}
