@if($errors->any())
    <div class="alert alert-warning">
        @foreach($errors->unique() as $error)
            <li>{{$error}}</li>
        @endforeach
    </div>
@endif