@extends('layout.layout')
@section('ex-style')
    <style>
        body {
            font-family: "Times New Roman";
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">

        <form action="/change_info_after_signup" method="post">
            @csrf

            <div class="mb-3">
                <label class="form-label">Tên: </label>
                <input type="text" name="username" id="" placeholder="chon username">
            </div>
            <div class="mb-3">
                <label class="form-label">Upload avatar: </label>
                <input type="file" name="avatar">
            </div>
{{--            <input type="hidden" name="token" value="{{ session()->get('data')->token }}">--}}
            <div class="col-auto">
                <input type="submit" class="btn btn-primary mb-3" value="Update">
            </div>
        </form>
    </div>

    @dump(session()->get('data'))
    @dump($user)

@endsection
