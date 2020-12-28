@extends('layout.layout')
@section('ex-style')
    <style>
        @parent
    body {
            background: whitesmoke;
        }

        .post {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }

        .posts {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            padding: 10px;
        }

        .posts {
            /*border: 1px solid gray;*/
            background: #fff;
            border-radius: 5px;
        }

    </style>
@endsection
@section('content')
    <div class="container-fluid col-10">
        <div class="post">
            <form method="post" action="add_post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <textarea class="form-control" name="described" placeholder="Đăng gì đó điiii" rows="3"></textarea>
                    <input type="hidden" name="token" value="{{ session()->get('token') }}">
                    <small id="emailHelp" class="form-text text-muted">
                        <ul>
                            <li><input type="text" name="status" placeholder="bạn đang cảm thấy..."></li>
                            <li><i class="fas fa-image"></i>&nbsp;<input type="file" name="image" accept="image/*"></li>
                            <li><i class="fas fa-video"></i>&nbsp;<input type="file" name="video"></li>
                            <li><i class="fas fa-link"></i>&nbsp;</li>
                        </ul>

                    </small>
                    <input type="submit" class="btn btn-primary" value="Post">
                </div>
                <div class="dropdown-divider"></div>

            </form>
        </div>
    </div>
    <div class="container-fluid col-6">
        @isset($posts)
            @foreach($posts as $p)
                <div class="pt-5">
                    <div class="posts">
                        <div class="posts-header">
                            <h3 class="title">{{$p->name}}</h3>
                            <p>{{ $p->created_at }}</p>
                        </div>
                        <div class="posts-body">
                            <p class="text">{{ $p->described }}</p>
                        </div>
                        @isset($p->media)
                            <img
                                src="{{ asset('/storage/'.$p->media) }}"
                                class="card-img-top" alt="...">
                        @endisset


                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="posts-cmt">
                                    <a>báo cáo</a>
                                    <form action="/report_post" method="post">
                                        @csrf
                                        <input type="hidden" name="token" value="{{ session()->get('token') }}">
                                        <input type="hidden" name="pid" value="{{ $p->id }}">
                                        <input type="text" name="subject" placeholder="chủ đề">
                                        <input type="text" name="details" placeholder="chi tiết">
                                        <input type="submit" value="báo cáo bài viết">
                                    </form>
                                </div>
                            </li>

                            <div class="posts-cmt">
                                <form action="/get_post" method="post">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ session()->get('token') }}">
                                    <input type="hidden" name="pid" value="{{ $p->id }}">
                                    <a href="/get_post/{{$p->id}} ">đi tới bài viết</a><br>
                                    <input type="submit" value="đi tới bài viết(json)">
                                </form>
                            </div>
                    </div>
                </div>
            @endforeach
        @endisset

    </div>
    @dump(session()->all())

@endsection
