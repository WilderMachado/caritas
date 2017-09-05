<?php

namespace caritas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Endereco extends Model
{
    use SoftDeletes;

    protected $softDelete = true;
    protected $fillable = ['logradouro','numero', 'complemento', 'bairro', 'cidade', 'uf'];
    protected $hidden = ['deleted_at'];
}
