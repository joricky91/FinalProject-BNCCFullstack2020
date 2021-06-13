@extends('layouts.app')

@section('content')
    <style>
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
            height: 400px;
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
        }
        .row>*{
            padding-right: 0;
            padding-left: 0;
        }
        #answer-count{
            margin-left: 280px;
        }
        #form_action{
            margin-left:280px;
        }
        .form-floating #content-textbox{
            padding-top:10px;
        }
    </style>

    <!-- PopUp Edit Question -->
    <div class="form-popup" id="popup">
        <div class="popup-content">
            <form method="POST" action="/home/{{$questions->id}}">
            @method('patch')
            @csrf
                <div class="mb-3">
                    <h4 class="mb-3">Edit your Question</h4>
                    <button type="button" class="btn-close position-absolute top-0 end-0 mt-3 mr-3" id="close"></button>
                    <label for="title" style="margin-left:-440px;">Title of Question</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Enter the Question Title" name="title" value="{{$questions->title}}">
                    @error('title')
                        <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <label for="content-textbox" style="margin-left:-420px;">Content of Question</label>
                <div class="form-floating">
                    <textarea class="form-control @error('content') is-invalid @enderror" placeholder="Enter the Question Content" id="content-textbox" style="height: 150px" name="content" value="{{old('content')}}">{{$questions->content}}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <input type="hidden" name="userId" value="{{ Auth::user()->id }}">
                <button type="submit" class="btn btn-primary my-3">Edit Question!</button>
            </form>
        </div>
    </div>

    <!-- Question -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="card" style="width: 65rem;">
                <div class="card-body">
                    <h4 class="card-title">{{$questions->title}}</h4>
                    <h6 class="card-subtitle mb-2 text-muted">Created at {{ $questions->created_at}} | Updated at {{ $questions->updated_at}} | By {{$questions->users['username']}}</h6>
                    <p class="card-text">{{$questions->content}}</p>
                    @if($questions->users['id'] == Auth::user()->id)
                        <a href="#" class="btn btn-primary" id="edit">Edit</a>
                        <form class="d-inline"method="POST" action="/home/{{$questions->id}}">
                        @method('delete')
                        @csrf
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Answers -->
            <hr class="mt-4" style="width: 65rem">
            <h5 class="card-text mt-4" id="answer-count">{{ $answer_count }} Answers</h5>
            @if($answer_count > 0)
                <!-- Answers Card -->
                @foreach ($answer as $key => $a)
                    <div class="card my-4 answercard" style="width: 65rem;">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">Created at {{ $a->created_at}} | Updated at {{ $a->updated_at}} | By {{$a->user['username']}}</h6>
                            <p class="card-text my-4">{{ $a->content }}</p>

                            <button type="submit" class="btn btn-info mt-3">Reply</button>

                            @if($a->user['id'] == Auth::user()->id)
                                <a href="#answercard" class="btn btn-primary mx-1 mt-3" onclick="display(`{{$a->content}}`, {{$a->id}}, {{$key}});">Edit</a>
                                <form action="/answer/{{$a->id}}" method="POST" class="d-inline">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="btn btn-danger mt-3">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <form method="POST" action="" id="form_action">
                        @method('patch')
                        @csrf
                        <div class="form-floating d-none" id="edit" style="width:65rem;">
                            <label for="content" style="margin-top:-9px;">Edit Your Answer Here</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" placeholder="Edit Your Answer Here" id="content" style="height: 150px" name="content"></textarea>
                            @error('content')
                                <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                            <input type="hidden" name="userId" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="questionId" value="{{ $questions->id }}">
                            <button type="submit" class="btn btn-primary my-3">Edit Your Answer!</button>
                            <a href="#edit" class="card-link mx-2 text-decoration-none" onclick="hide({{$key}});">Cancel</a>
                        </div>
                    </form>
                @endforeach
            @endif

             <!-- Answer Box -->
             <hr class="mt-1 mb-5" style="width: 65rem">
             <form method="POST" action="/answer" style="width: 65rem;">
                @csrf
                <div class="form-floating">
                    <h4 class="mb-2">Write a Comment</h4>
                    <label for="content" class="mt-4">Enter Your Answer Here</label>
                    <textarea class="form-control @error('content') is-invalid @enderror" placeholder="Enter Your Answer Here" id="content" style="height: 150px" name="content"></textarea>
                    @error('content')
                        <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <input type="hidden" name="userId" value="{{ Auth::user()->id }}">
                <input type="hidden" name="questionId" value="{{ $questions->id }}">
                <button type="submit" class="btn btn-primary my-3">Post Your Answer!</button>
                
            </form>
        </div>
    </div>
    
    <script>
        document.getElementById("edit").addEventListener("click", function(){
            document.querySelector(".form-popup").style.display = "flex";
        });
        document.getElementById("close").addEventListener("click", function(){
            document.querySelector(".form-popup").style.display = "none";
        });

        function display(x,y,z){
            var form_edit = $("#form_action #edit");
            var form = $(".row #form_action");
            var content = $(".row #form_action #edit #content");
            form_edit[z].className = "form-floating edit d-block";
            form[z].action = "/answer/" + y;
            content[z].value = x;
        }
        function hide(k){
            var form = $("#form_action #edit");
            form[k].className = "form-floating edit d-none";
        }
    </script>
    
@endsection