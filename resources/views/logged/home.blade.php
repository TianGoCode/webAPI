@extends('layout.layout')
@section('content')
    <div class="container-fluid col-10" >
        <div class="post">
            <form method="post" action="add_post">
                @csrf
                <div class="form-group">
                    <textarea class="form-control" name="described" placeholder="Đăng gì đó điiii" rows="3"></textarea>
                    <input type="hidden" name="token" value="{{ session()->get('token') }}">
                    <input type="text" name="status" placeholder="bạn đang cảm thấy...">
                    <small id="emailHelp" class="form-text text-muted">
                        <ul>
                            <li><i class="fas fa-image"></i>&nbsp;<input type="file" name="image"></li>
                            <li><i class="fas fa-video"></i>&nbsp;<input type="file" name="video"></li>
                            <li><i class="fas fa-link"></i>&nbsp;</li>
                        </ul>
                    </small>
                </div>
                <div class="dropdown-divider"></div>
                <input type="submit" class="btn btn-primary" value="Post">
            </form>
        </div>
    </div>

@endsection
