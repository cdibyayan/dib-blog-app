@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @if (session('error'))
                    <div class="alert alert-danger">
                      {{ session('error') }}
                    </div>
                    @endif
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Add New Blog') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="name" class="col-md-1 col-form-label text-md-end">{{ __('Title') }}</label>

                            <div class="col-md-11">
                                <input id="name" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ $post->title }}" required  autofocus>

                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-1 col-form-label text-md-end">{{ __('Post Description') }}</label>

                            <div class="col-md-11">
                                <textarea id="content" class="form-control @error('content') is-invalid @enderror" name="content">
                                    {{$post->content}}
                                </textarea>
                                
                                @error('content')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-3 col-form-label text-md-end">{{ __('Set Featured Image') }}</label>

                            <div class="col-md-6">
                                <input id="thumbnail" type="file" class="form-control @error('thumbnail') is-invalid @enderror" name="thumbnail">

                                @error('thumbnail')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <img class="img-fluid" src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{$post->thumbnail}}">
                            </div>
                        </div>
           

                        <div class="row mb-0">
                            <div class="col-md-3 offset-md-1">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Edit Blog') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{url('tinymce/tinymce.min.js')}}"></script>
<script>
    tinymce.init({
        selector: '#content'
    });
</script>
@endsection
