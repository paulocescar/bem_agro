@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Search') }}</div>

                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Github username</label>
                        <input type="text" class="form-control" id="username" value="paulocescar" aria-describedby="username" placeholder="Enter github username">
                    </div>
                    <a class="btn btn-primary" id="handleSubmit">Submit</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#handleSubmit').on('click',function(){
        $.ajax({
            url: '/githubUser',
            data: { 'username': $('#username').val() },
            dataType: 'json',
            success: function(data){
                console.log(data)
                if(!data.id){
                    alert('Not Found')
                }
            },
            error: function(err){
                console.log(err)
            }
        })
    })
    
</script>
@endsection
