<div id="emails">
    <div class="form-group">
        {!! Form::label ('emails[0][email]', 'E-mail: ',[ 'class'=>'control-label col-xs-2']) !!}
        <div class="col-xs-5">
            {!! Form::text ('emails[0][email]', null, ['class'=>'form-control']) !!}
        </div>
        {!! Form::button('-',['class'=>'btn btn-danger  btn-remover-email']) !!}
    </div >
    <div class="form-group">
        <div class="col-xs-offset-2 col-xs-10">
            {!! Form::button ('Adicionar E-mail', ['class'=>'btn btn-success', 'id'=>'btn-adicionar-email']) !!}
        </div>
    </div>
</div>