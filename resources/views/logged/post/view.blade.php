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

        .feed-comment {
            border: 1px solid black;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid col-6">

        <div class="pt-5">
            <div class="posts">
                <div class="posts-header">
                    <h3 class="title">{{ $post->postedBy->name }}</h3>
                    <p>{{$post->created_at}}</p>
                </div>
                <div class="posts-body">
                    <p class="text">{{ $post->described }}</p>
                </div>
                @isset($p->media)
                    <img
                        src="{{ asset('/storage/'.$p->media) }}"
                        class="card-img-top" alt="...">
                @endisset

                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        @if(session()->get('data')->id == $post->author_id)
                            <a href="/edit_post/{{ $post->id }}">chỉnh sửa bài viết</a>
                        @else
                            <a href="#">chỉnh sửa bài viết</a>
                        @endif
                    </li>

                    <li class="list-group-item">
                        <div class="posts-cmt">
                            <a>báo cáo</a>
                            <form action="/report_post" method="post">
                                @csrf
                                <input type="hidden" name="token" value="{{ session()->get('token') }}">
                                <input type="hidden" name="id" value="{{ $post->id }}">
                                <input type="text" name="subject" placeholder="chủ đề">
                                <input type="text" name="detail" placeholder="chi tiết">
                                <input type="submit" value="báo cáo bài viết">
                            </form>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <form action="/like" method="post">
                            @csrf
                            <input type="hidden" name="token" value="{{ session()->get('token') }}">
                            <input type="hidden" name="id" value="{{ $post->id }}">

                            <input type="submit" value="like">
                            @isset($likes)
                                <p>{{$likes}} lượt thích</p>
                            @endisset
                        </form>
                    </li>
                </ul>
                <div class="posts-cmt">
                    <form action="/get_post" method="post">
                        @csrf
                        <input type="hidden" name="token" value="{{ session()->get('token') }}">
                        <input type="hidden" name="pid" value="{{ $post->id }}">

                        <input type="submit" value="đi tới bài viết(json)">
                    </form>
                </div>


                <div class="posts-cmt">
                    <form action="/set_comment" method="post">
                        @csrf
                        <input type="text" name="comment" placeholder="bình luận bài viết">
                        <input type="hidden" name="token" value="{{ session()->get('token') }}">
                        <input type="hidden" name="id" value="{{ $post->id }}">
                        <input type="submit" value="bình luận">
                    </form>
                </div>
                <div class="posts-cmt">
                    <form action="/get_comment" method="post">
                        @csrf
                        <input type="hidden" name="token" value="{{ session()->get('token') }}">
                        <input type="number" name="count" placeholder="số lượng cmt mỗi trang...">
                        <input type="hidden" name="id" value="{{ $post->id }}">

                        <input type="submit" value="lấy bình luận">

                    </form>
                </div>


                <div class="posts-cmt pt-5 md-5">
                    @isset($comments)
                        @foreach($comments as $c)
                            <div class="feed-comment">
                                <h5 class="title">{{ $c->by->name }}</h5>
                                <p>{{$c->created_at}}</p>
                                <br>
                                <p>{{ $c->content }}</p>
                            </div>
                        @endforeach
                        @dump($comments)
                    @endisset
                </div>
            </div>
        </div>


    </div>
    @dump($post)
    @dump(session()->all())
@endsection
