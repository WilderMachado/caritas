<?php

namespace caritas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instituicao extends Model
{
    use SoftDeletes;

    protected $softDelete = true;
    protected $fillable = ['nome'];
    protected $hidden = ['deleted_at'];

    public function telefones()
    {
        return $this->morphMany(Telefone::class, 'dono');
    }

    public function emails()
    {
        return $this->morphMany(Email::class, 'dono');
    }

    public function endereco()
    {
        return $this->belongsTo(Endereco::class);
    }

    public function membros()
    {
        return $this->hasMany(Membro::class);
    }
}
