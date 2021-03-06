@extends('layouts.app')

@section('content')
    <style>
        html, body{
            background-color: #1f3e75;
        }
        h3{
            color: white;
        }
        .form-popup {
            background: rgba(0,0,0,0.6);
            width: 100%;
            heigth: 100%;
            position: absolute;
            top: 0;
            bottom: 0;
            display: none;
            justify-content: center;
            align-items: center;
            text-align: center;
            z-index: 999;
        }
        .popup-content{
            height: 350px;
            width: 600px;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            position: relative;
        }
        .popup{
            width: 20%;
            position: fixed;
            bottom: 0;
            right: 0;
            margin-bottom: -20px;
            z-index: 9990;
        }
        .setting{
            width: 40%;
        }
        .card-setting{
            width: 35rem;
        }
        #form_action{
            width: 90%;
            margin-left:auto;
            margin-right:auto;
            color: gray;
        }
        #form_action input, label{
            display: flex;
        }
        #form_actions{
            width: 90%;
            margin-left:auto;
            margin-right:auto;
            color: gray;
        }
        #form_upload{
            width: 40%;
            margin-left:auto;
            margin-right:auto;
            color: gray;
        }
        .button{
            width: 179px;
            height: 40px;
            padding: 8px;
        }
        .width{
            width: 415px;
        }
        .button{
            background-color: white;
            color: #5e72e4;
        }
        .button .fas{
            color: #5e72e4;
        }
        .button:focus{
            background-color: #5e72e4;
            color: white;
        }
        .button:focus .fas{
            color: white;
        }
        #active{
            background-color: #5e72e4; 
            color: white; 
            font-weight: bold;
        }
        #active:hover{
            box-shadow: 1px 1px 7px #888888;
        }
        #photo_profile{
            width: 200px;
            height: 200px;
            text-align: center;
            object-fit: cover;
        }
        @media (-webkit-device-pixel-ratio: 1.50) {
            * {
                zoom: 0.98;
            }
            .button{
                width: 175px;
            }
            .width{
                width: 455px;
            }
            .card-setting{
                width: 33.6rem;
            }
        }
        .buttonAdd{
            background-color: #2dce89;
            color: white;
        }
    </style>

    <!-- PopUp Status -->
    <div class="popup">
        @if (session('status'))
            <div class="alert alert-success" id="notif">
                {{ session('status') }}
                <button type="button" class="btn-close position-absolute top-0 end-0 mt-3 mr-3" id="btn-close"></button>
            </div>
        @endif
    </div>

    <div class="popup">
        @if (session('danger'))
            <div class="alert alert-danger" id="notif">
                {{ session('danger') }}
                <button type="button" class="btn-close position-absolute top-0 end-0 mt-3 mr-3" id="btn-close"></button>
            </div>
        @endif
    </div>

    <!-- PopUp Form -->
    <div class="form-popup" id="popup">
        <div class="popup-content">
            <form method="POST" action="/home">
            @csrf
                <div class="mb-3">
                    <h4 class="mb-3">Have Any Questions?</h4>
                    <button type="button" class="btn-close position-absolute top-0 end-0 mt-3 mr-3" id="close"></button>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Enter the Question Title" name="title" value="{{old('title')}}">
                    @error('title')
                        <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="form">
                    <textarea class="form-control @error('content') is-invalid @enderror" placeholder="Enter the Question Content" id="content" style="height: 150px" name="content" value="{{old('content')}}"></textarea>
                    @error('content')
                        <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <input type="hidden" name="userId" value="{{ Auth::user()->id }}">
                <button type="submit" class="btn buttonAdd my-3">Add Question!</button>
            </form>
        </div>
    </div>

    <div class="container setting">
        <div class="row justify-content-center">
            <h3 class="card-title mb-3">Settings</h3>
            <div class="text-center">
                <a href="#" class="btn mx-1 mt-3 mb-3 button" onclick="show_profile()">
                    <i class="fas fa-user"></i> Profile
                </a>
                <a href="#" class="btn mx-1 mt-3 mb-3 button" onclick="show_avatar()">
                    <i class="fas fa-cloud-upload-alt"></i> Avatar
                </a>
                <a href="#" class="btn mx-1 mt-3 mb-3 button" onclick="show_password()">
                    <i class="fas fa-unlock-alt"></i> Password
                </a>
            </div>
            <div class="card card-setting">
                <div class="card-body">
                    <!-- Profile -->
                    <form method="POST" action="/settingsProfile/{{$users->id}}" id="form_action" class="form_profile d-block text-center">
                        @method('patch')
                        @csrf
                        <h4 class="text-center mt-4">Update Your Profile</h4>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="background-color: white">
                                        <i class="fa fa-user" style="color: #a7adb2"></i>
                                    </span>
                                    <input class="form-control width" type="text" id="name" value="{{$users->name}}" name="name" class="form-control @error('name') is-invalid @enderror">
                                </div>
                            </div>
                        </div>
                        @error('name')
                            <div class="invalid-feedback">{{$message}}</div>
                        @enderror

                        <div class="form-group">
                            <label for="username">Username</label>
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="background-color: white">
                                        <i class="fa fa-at" style="color: #a7adb2"></i>
                                    </span>
                                    <input class="form-control width" type="text" value="{{$users->username}}" id="username" name="username" class="form-control @error('username') is-invalid @enderror">
                                </div>
                            </div>
                        </div>
                        @error('username')
                            <div class="invalid-feedback">{{$message}}</div>
                        @enderror

                        <div class="form-group">
                            <label for="email">Email</label>
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="background-color: white">
                                        <i class="fa fa-envelope" style="color: #a7adb2"></i>
                                    </span>
                                    <input class="form-control width" type="text" value="{{$users->email}}" id="email" name="email" class="form-control @error('email') is-invalid @enderror">
                                </div>
                            </div>
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{$message}}</div>
                        @enderror

                        <button type="submit" class="btn my-3 mb-4 justify" id="active">UPDATE PROFILE</button>
                    </form>

                    <!-- Avatar -->
                    <form method="POST" action="/settingsPhoto" enctype="multipart/form-data" id="form_upload" class="form-photo d-none">
                        <h4 class="text-center mt-4 mb-4">Upload photo</h4>
                        @csrf
                        @if($users->photo == NULL)
                            <img class="rounded mx-auto mb-4 d-block img-thumbnail rounded-circle photo" id="photo_profile" src="{{asset('images/profile-placeholder.png')}}" alt="">
                        @else
                            <img class="rounded mx-auto mb-4 d-block img-thumbnail rounded-circle photo" id="photo_profile" src="{{'/images/'.$users->photo}}" alt="">
                        @endif
                        <div class="form-group" style="height: 3.5rem;">
                            
                            <input type="file" class="form-control-file text-center" id="photo" name="photo" onchange="readURL(this);">
                            <input type="hidden" name="userId" value="{{ Auth::user()->id }}">
                        </div>
                        <button type="submit" class="btn mb-4" id="active">UPLOAD</button>
                    </form>

                    <!-- Password -->
                    <form method="POST" action="/settingsPassword/{{$users->id}}" id="form_actions" class="form-password d-none text-center">
                        @method('patch')
                        @csrf
                        <h4 for="content" class="text-center mt-4">Change Your Password</h4>
                        <div class="form-group mt-4">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="background-color: white">
                                        <i class="fas fa-unlock-alt" style="color: #a7adb2"></i>
                                    </span>
                                    <input type="password" id="currentPassword" name="currentPassword" placeholder="Current Password" class="form-control width @error('currentPassword') is-invalid @enderror d-block"> 
                                </div>
                            </div>
                        </div>
                        @error('currentPassword')
                            <div class="invalid-feedback">{{$message}}</div>
                        @enderror

                        <div class="form-group mt-4">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="background-color: white">
                                        <i class="fas fa-unlock-alt" style="color: #a7adb2"></i>
                                    </span>
                                    <input type="password" id="newPassword" name="newPassword" placeholder="New Password" class="form-control width @error('newPassword') is-invalid @enderror d-block">
                                </div>
                            </div>
                        </div>
                        @error('newPassword')
                            <div class="invalid-feedback">{{$message}}</div>
                        @enderror

                        <div class="form-group mt-4">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="background-color: white">
                                        <i class="fas fa-unlock-alt" style="color: #a7adb2"></i>
                                    </span>
                                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" class="form-control width @error('confirmPassword') is-invalid @enderror d-block">
                                </div>
                            </div>
                        </div>
                        @error('confirmPassword')
                            <div class="invalid-feedback">{{$message}}</div>
                        @enderror

                        <button type="submit" class="btn my-3 mb-4" id="active">CHANGE PASSWORD</button> 
                   </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // PopUp Form
        document.getElementById("askbutton").addEventListener("click", function(){
            document.querySelector(".form-popup").style.display = "flex";
        });
        document.getElementById("close").addEventListener("click", function(){
            document.querySelector(".form-popup").style.display = "none";
        });

        // Notification Close Button 
        document.getElementById("btn-close").addEventListener("click", function(){
            document.querySelector("#notif").style.display = "none";
        });
        
        // Notification Auto Close
        setTimeout(function(){ 
            document.querySelector("#notif").style.display = "none";
        }, 5000);

        // Close Button Function
        function closing(){
            document.getElementById("test").style.display = "none";
        }

        function show_profile(){
            document.getElementById("form_actions").className = "form-password d-none";
            document.getElementById("form_action").className = "form-profile d-block text-center";
            document.getElementById("form_upload").className = "form-photo d-none";
        }
        function show_avatar(){
            document.getElementById("form_action").className = "form-profile d-none";
            document.getElementById("form_upload").className = "form-photo d-block text-center";
            document.getElementById("form_actions").className = "form-password d-none";
        }
        function show_password(){
            document.getElementById("form_actions").className = "form-password d-block text-center";
            document.getElementById("form_action").className = "form-profile d-none";
            document.getElementById("form_upload").className = "form-photo d-none";
        }
        function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#photo_profile')
                    .attr('src', e.target.result)
                    .width(200)
                    .height(200);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
@endsection