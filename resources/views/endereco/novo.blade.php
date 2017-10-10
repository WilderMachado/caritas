<div class="form-group">
{!! Form::label ('endereco[logradouro]', 'Logradouro: ',[ 'class'=>'control-label col-xs-2 col-md-3']) !!}
<div class="col-xs-5 col-md-6">
    {!! Form::text ('endereco[logradouro]', null, ['class'=>'form-control', 'placeholder'=>'']) !!}
</div>
</div>
<div class="form-group">
    {!! Form::label ('endereco[numero]', 'Número: ',[ 'class'=>'control-label col-xs-2 col-md-3']) !!}
    <div class="col-xs-5 col-md-6">
        {!! Form::text ('endereco[numero]', null, ['class'=>'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label ('endereco[complemento]', 'Complemento: ',[ 'class'=>'control-label col-xs-2 col-md-3']) !!}
    <div class="col-xs-5 col-md-6">
        {!! Form::text ('endereco[complemento]', null, ['class'=>'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label ('endereco[bairro]', 'Bairro: ',[ 'class'=>'control-label col-xs-2 col-md-3']) !!}
    <div class="col-xs-5 col-md-6">
        {!! Form::text ('endereco[bairro]', null, ['class'=>'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label ('endereco[cidade]', 'Cidade: ',[ 'class'=>'control-label col-xs-2 col-md-3']) !!}
    <div class="col-xs-5 col-md-6">
        {!! Form::text ('endereco[cidade]', null, ['class'=>'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label ('endereco[uf]', 'UF: ',[ 'class'=>'control-label col-xs-2 col-md-3']) !!}
    <div class="col-xs-5 col-md-6">
        {!! Form::select('endereco[uf]',config::get('constantes.uf'), null, ['class'=>'form-control', 'placeholder'=>'']) !!}
    </div>
</div>
