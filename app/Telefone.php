<?php

namespace caritas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Telefone extends Model
{
    use SoftDeletes;

    protected $softDelete = true;
    protected $fillable = ['ddd', 'numero', 'tipo'];
    protected $hidden = ['deleted_at'];

    public function dono()
    {
        return $this->morphTo();
    }
}
