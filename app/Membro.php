<?php

namespace caritas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membro extends Model
{
    use SoftDeletes;

    protected $softDelete = true;
    protected $fillable = ['nome', 'instituicao_id', 'cargo'];
    protected $hidden = ['deleted_at'];

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class);
    }

    public function endereco()
    {
        return $this->belongsTo(Endereco::class);
    }

    public function telefones()
    {
        return $this->morphMany(Telefone::class, 'dono');
    }

    public function emails()
    {
        return $this->morphMany(Email::class, 'dono');
    }
}
