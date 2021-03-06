<div id="telefones">
    @foreach(config::get('constantes.tipoTelefone') as $tipo => $valor)
        {!! Form::hidden($valor, $tipo ,['class'=>'tipo-telefone']) !!}
    @endforeach
    @foreach($entidade->telefones as $indice => $telefone)
        <div class="form-group">
            {!! Form::label ("telefones[$indice][ddd]", 'Telefone: ',[ 'class'=>'control-label col-xs-2 col-md-3']) !!}
            <div class="col-xs-1 col-md-1">
                {!! Form::text ("telefones[$indice][ddd]", $telefone->ddd, ['class'=>'form-control', 'placeholder'=>'DDD', 'maxlength'=>2]) !!}
            </div>
            <div class="col-xs-2 col-md-3">
                {!! Form::text ("telefones[$indice][numero]", $telefone->numero, ['class'=>'form-control', 'placeholder'=>'Número','maxlength'=>9]) !!}
            </div>
            <div class="col-xs-2 col-md-2">
                {!! Form::select("telefones[$indice][tipo]",config::get('constantes.tipoTelefone'), $telefone->tipo, ['class'=>'form-control', 'placeholder'=>'Tipo']) !!}
            </div>
                {!! Form::button('',['class'=>'btn btn-danger btn-remover-telefone glyphicon glyphicon-remove']) !!}
        </div>
    @endforeach
    <div class="form-group">
        <div class="col-xs-offset-2 col-xs-10 col-md-offset-3 col-md-9">
            {!! Form::button ('Adicionar Telefone', ['class'=>'btn btn-success', 'id'=>'btn-adicionar-telefone']) !!}
        </div>
    </div>
</div>