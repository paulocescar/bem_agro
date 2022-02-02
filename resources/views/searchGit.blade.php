@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Search github users') }}</div>

                <div class="card-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="username" value="paulocescar" aria-describedby="username" placeholder="Enter github username">
                    </div>
                    <a class="btn btn-primary" id="handleSubmit">Submit</a>
                </div>
            </div>
            <div class="card mt-3" id="card-user">
                <div class="card-header">{{ __('Users') }}</div>
                
                @foreach($data as $u)
                <div class="card-body d-flex justify-content-between" id="user-{{$u->git_id}}">
                        <div class="w-25 h-25 d-inline-block d-flex justify-content-center">
                            <img src="{{$u->avatar_url}}" id="avatar" alt="avatar" class="w-50 h-25 align-self-center rounded-circle" />
                        </div>
                        <div class="w-50 d-inline-block">
                            <div class="w-100 d-inline-block">
                                <b onclick="details('{{$u->git_id}}')" style="cursor: pointer; z-index: 100;">{{$u->username}}</b> 
                            </div>
                            <div class="w-100 d-inline-block">
                                <a target="_blank" href="{{$u->git_url}}" id="url">{{$u->repositories}} repositórios</a>
                            </div>
                            <div class="w-25 d-inline-block">
                                <span id="followers" style="font-size: 10px;">{{$u->followers}} followers</span> 
                            </div>
                            <div class="w-25 d-inline-block">
                                <span id="following" style="font-size: 10px;">{{$u->following}} following</span>
                            </div>
                        </div>

                        <div class="h-100 float-right">
                            <button class="btn btn-info mt-3" style="display: none" id="a-{{$u->git_id}}" onclick="searchUser('{{$u->git_id}}', '{{$u->username}}')" style="color:white;" data-bs-toggle="collapse" href="#user-collapse-{{$u->git_id}}" role="button" aria-expanded="false" aria-controls="collapseExample">Details</button>
                            <a class="btn btn-danger mt-3" onclick="remove('{{$u->git_id}}')">Remove</a>
                        </div>

                        <hr>
                </div>
                <div class="collapse" id="user-collapse-{{$u->git_id}}">
                    <div class="card card-body" style="background: #444; color:white;">
                    <a class="btn btn-danger" style="width: 35px!important;" onclick="removeCollapse('user-collapse-{{$u->git_id}}')">X</a>
                        <p id="text-collapse-{{$u->git_id}}"></p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<script>
    function details(id){
        console.log('aqui')
        $('#a-'+id).click()
    }
    function removeCollapse(id){
        $('#'+id).removeClass('show');
    }
    function remove(id){
        $('#user-'+id).remove();
        $('#user-collapse-'+id).remove();
        $.ajax({
            method: 'POST',
            url: '/gituserRemove',
            data: { 'id': id, '_token': '{{ csrf_token() }}' },
            dataType: 'json',
            success: function(data){
                console.log(data)
            }, error: function(err){
                console.log(data)
            }
        })
    }

    
    function searchUser(id, username){
        if($('#text-collapse-'+id).text() == ''){
            $.ajax({
                method: 'POST',
                url: '/githubUser',
                data: { 'username': username, '_token': '{{ csrf_token() }}' },
                dataType: 'json',
                success: function(data){
                    console.log(data)
                    $('#text-collapse-'+id).text(JSON.stringify(data))
                }, error: function(err){
                    console.log(data)
                }
            })
        }
    }
    $('#handleSubmit').on('click',function(){
        $.ajax({
            method: 'POST',
            url: '/githubUserAdd',
            data: { 'username': $('#username').val(), '_token': '{{ csrf_token() }}' },
            dataType: 'json',
            success: function(data){
                console.log(data)
                if(!data.id){
                    alert('Not Found or user in your list!')
                }else{
                    user = $('#card-user')
                    div = $(`<div class="card-body d-flex justify-content-between" id="user-${data.id}">`+
                                `<div class="w-25 h-25 d-inline-block d-flex justify-content-center">`+
                                    `<img src="${data.avatar_url}" alt="avatar" class="w-50 h-25 align-self-center rounded-circle" />`+
                                `</div>`+
                                `<div class="w-50 d-inline-block">`+
                                    `<div class="w-100 d-inline-block">`+
                                        `<b>${data.login}</b> `+
                                    `</div>`+
                                    `<div class="w-100 d-inline-block">`+
                                        `<a target="_blank" href="${data.html_url}">${data.public_repos} repositórios</a>`+
                                    `</div>`+
                                    `<div class="w-25 d-inline-block">`+
                                        `<span style="font-size: 10px;">${data.followers} followers</span> `+
                                    `</div>`+
                                    `<div class="w-25 d-inline-block">`+
                                        `<span style="font-size: 10px;">${data.following} following</span>`+
                                    `</div>`+
                                `</div>`+

                                `<div class="h-100 float-right">`+
                                    `<a class="btn btn-info mt-3" onclick="searchUser('${data.id}', '${data.login}')" style="color:white;" data-bs-toggle="collapse" href="#user-collapse-${data.id}" role="button" aria-expanded="false" aria-controls="collapseExample">Details</a>`+
                                    `<a class="btn btn-danger mt-3" onClick="remove('${data.id}')">Remove</a>`+
                                `</div>`+

                                `<hr>`+
                            `</div>`+
                            
                            `<div class="collapse" id="user-collapse-${data.id}">`+
                                `<div class="card card-body" style="background: #444; color:white;">`+
                                `<a class="btn btn-danger" style="width: 35px!important;" onclick="removeCollapse('user-collapse-${data.id}')" id="JsonGit-${data.id}">X</a>`+
                                    `<p id="text-collapse-${data.id}">${JSON.stringify(data)}</p>`+
                                `</div>`+
                            `</div>`)

                    user.append(div)
                    $('#card-user').removeClass('invisible')

                    $.ajax({
                        method: 'POST',
                        url: '/gituserAdd',
                        data: { 'username': data.login,
                            'username': data.login,
                            'git_url': data.html_url,
                            'git_id': data.id,
                            'avatar_url': data.avatar_url, 
                            'repositories': data.public_repos, 
                            'following': data.following, 
                            'followers': data.followers,
                            '_token': '{{ csrf_token() }}' },
                        dataType: 'json',
                        success: function(data){
                            console.log(data)
                        }
                    })
                }
            },
            error: function(err){
                console.log(err)
            }
        })
    })
    
</script>
@endsection
