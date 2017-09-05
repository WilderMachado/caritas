<?php

namespace caritas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Email extends Model
{
    use SoftDeletes;

    protected $softDelete = true;
    protected $fillable = ['email'];
    protected $hidden = ['deleted_at'];

    public function dono()
    {
        return $this->morphTo();
    }
}
