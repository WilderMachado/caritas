<div id="emails">
    <div class="form-group">
        {!! Form::label ('emails[0][email]', 'E-mail: ',[ 'class'=>'control-label col-xs-2 col-md-3']) !!}
        <div class="col-xs-5 col-md-6">
            {!! Form::text ('emails[0][email]', null, ['class'=>'form-control']) !!}
        </div>
        {!! Form::button('',['class'=>'btn btn-danger  btn-remover-email glyphicon glyphicon-remove']) !!}
    </div >
    <div class="form-group">
        <div class="col-xs-offset-2 col-md-offset-3 col-xs-10 col-md-9">
            {!! Form::button ('Adicionar E-mail', ['class'=>'btn btn-success', 'id'=>'btn-adicionar-email']) !!}
        </div>
    </div>
</div>