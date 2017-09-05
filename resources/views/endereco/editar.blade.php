<div class="form-group">
    {!! Form::label ('endereco[logradouro]', 'Logradouro: ',[ 'class'=>'control-label col-xs-2']) !!}
    <div class="col-xs-5">
        {!! Form::text ('endereco[logradouro]', $entidade->endereco->logradouro, ['class'=>'form-control', 'placeholder'=>'']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label ('endereco[numero]', 'Número: ',[ 'class'=>'control-label col-xs-2']) !!}
    <div class="col-xs-5">
        {!! Form::text ('endereco[numero]', $entidade->endereco->numero, ['class'=>'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label ('endereco[complemento]', 'Complemento: ',[ 'class'=>'control-label col-xs-2']) !!}
    <div class="col-xs-5">
        {!! Form::text ('endereco[complemento]', $entidade->endereco->complemento, ['class'=>'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label ('endereco[bairro]', 'Bairro: ',[ 'class'=>'control-label col-xs-2']) !!}
    <div class="col-xs-5">
        {!! Form::text ('endereco[bairro]', $entidade->endereco->bairro, ['class'=>'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label ('endereco[cidade]', 'Cidade: ',[ 'class'=>'control-label col-xs-2']) !!}
    <div class="col-xs-5">
        {!! Form::text ('endereco[cidade]', $entidade->endereco->cidade, ['class'=>'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label ('endereco[uf]', 'UF: ',[ 'class'=>'control-label col-xs-2']) !!}
    <div class="col-xs-5">
        {!! Form::select('endereco[uf]',Config::get('constantes.uf'), $entidade->endereco->uf, ['class'=>'form-control', 'placeholder'=>'']) !!}
    </div>
</div>
