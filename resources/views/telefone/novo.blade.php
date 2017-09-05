<div id="telefones">
    @foreach(Config::get('constantes.tipoTelefone') as $tipo => $valor)
        {!! Form::hidden($valor, $tipo ,['class'=>'tipo-telefone']) !!}
    @endforeach
    <div class="form-group">
        {!! Form::label ('telefones[0][ddd]', 'Telefone: ',[ 'class'=>'control-label col-xs-2']) !!}
        <div class="col-xs-1">
            {!! Form::text ('telefones[0][ddd]', null, ['class'=>'form-control', 'placeholder'=>'DDD', 'maxlength'=>2]) !!}
        </div>
        <div class="col-xs-2">
            {!! Form::text ('telefones[0][numero]', null, ['class'=>'form-control', 'placeholder'=>'Número','maxlength'=>9]) !!}
        </div>
        <div class="col-xs-2">
            {!! Form::select('telefones[0][tipo]',Config::get('constantes.tipoTelefone'), null, ['class'=>'form-control', 'placeholder'=>'Tipo']) !!}
        </div>
        {!! Form::button('-',['class'=>'btn btn-danger btn-remover-telefone']) !!}
    </div>

    <div class="form-group">
        <div class="col-xs-offset-2 col-xs-10">
            {!! Form::button ('Adicionar Telefone', ['class'=>'btn btn-success', 'id'=>'btn-adicionar-telefone']) !!}
        </div>
    </div>
</div>