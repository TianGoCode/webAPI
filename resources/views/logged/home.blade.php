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
            <form method="post" action="add_post">
                @csrf
                <div class="form-group">
                    <textarea class="form-control" name="described" placeholder="Đăng gì đó điiii" rows="3"></textarea>
                    <input type="hidden" name="token" value="{{ session()->get('token') }}">
                    <small id="emailHelp" class="form-text text-muted">
                        <ul>
                            <li><input type="text" name="status" placeholder="bạn đang cảm thấy..."></li>
                            <li><i class="fas fa-image"></i>&nbsp;<input type="file" name="image"></li>
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
                        <img
                            src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxIREhUSEhIQEBUVEA8VFRUQDxAQDw8QFREWFhUVFRUYHSggGBolGxUVITEhJSkrLi4uFx8zODMtNygtLisBCgoKDg0OFxAQFy0dHR0tKy0rLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tKy0tLS0tLTctLS0tNzctNy03LSsrK//AABEIAKgBKwMBIgACEQEDEQH/xAAbAAACAwEBAQAAAAAAAAAAAAACAwABBAUGB//EAD8QAAIBAgMFBQYCCQIHAAAAAAABAgMRBCExEkFRYYEFEyJxkQYyUqGxwRRiIzNCY3KCktHwB+EkQ3OistLx/8QAGQEAAwEBAQAAAAAAAAAAAAAAAAECAwQF/8QAJREAAgICAgEEAwEBAAAAAAAAAAECEQMxEiETBDJBUSJhcRQz/9oADAMBAAIRAxEAPwD3xArEsI1BIWQhgBON0xHc8/kaWgbDQNIR3PP5E7nmaUiNA2xUjN3PMnc8/kPsWhcmOkZ+55ld0aWgNkTkw4oV3XMruuY9orZI5yHwRn2CKA1ouwvJIOCFbBNgbYtIPJIfBCdgGUbHO7V7VdKrGCta15OXPRL0OhQrqcdqPVb0zP8A0O6srxdXREXYZYRWxKUow1lJ9EhSztbYLEnpBWINkgGg8s/sXCIFy0yWJYPNP7DxxIQhBeaf2HjRVybRZUg80/sOEStorbBKJ88/sfjiGp3LaBpLMa4ibc+2NJLQqxLDNkrZFxHYuxLDNkmyHELOpYlhuwTZPTowsTYqw7ZK2RNAJsQc4AWAYtxJYJtLevVC5V4rWUfVEsC9kqwDrxejv5JsrvOUn/JL+xI0NsC2VHaekJ/02+oXdT+B+sf7kuyrJcphKhU+FdZINYSp+T1f9hU2FibEHvBT+KC6NkeDaV3NL+S31ZLiwszkRlxONhHKMnN8bJIy/iZS8jmlminS7NFBs877ZUJVJS7pvbSpyVnrZWcfM09kUasUpvapXjG8G7yuuLOlgsB45VN8nq87eRqxGDm4tqzeeraV+ZyNymtG9xiIdeVrpy87th4LDqp+k0knm/szznZXauOjJwrYdW7xxtCNoRp2dpqd89LZ63Wh6LE4SqqM1h5KFSUfC5aKX+bwljaaTZKn0zdJ21y+gDqeb8otjvZ2hVeHXfNutFeL3c/TI3xlfTM7ccXLbMJSSOSpPdGf9EglGfwS9LHTkXY08X7J5nMVOfwP1S+5bo1Pht5yR0IxJKIeJBzZznhanCP9X+wtUZv4fmdXZM8aXnq974i8SGpNmP8ADy4x9GW8JL4l0j/uapU81rv3sJUlwF40O2ZMPRs73vrusPcQqULfMOw1GhWJ2SbI6xNkdAI2SbI/ZK2QoDqbJWyNsUz0qMBWyTZGFMhgDGCbs9CfhIfCn53ZISzQ9McKaBiFhYL9iPoidyvhj6IeUwaQWHhtbZA1sTFScXe9losswHUscjtzFbUJKEnGdlZp+JZ8TOeWMF2NRbOlOtwjL5AOo/h+aPNQjlnXxEnbPxpfYXKnD4qz860vscr9XA18LPVd6+C6sqWI5wX8x5NUqN/FCcvOvV/uFVoYd+5Qg+O3UqP0zJ/14/sfhkemnjoLWpT6NHC7T7W28lp9TnrCxjpCEH+SNiRoHJn9Q59R0bY8aj2y6VO7ubIU7LIqEBsV5ERjQSkwMBKWa3ps39/ulfmYJRad11Hp3XPmVDroiXYdWpC2SS+rPL+3/adeFCLw01Tkp+K2so23dTqdoYSNVbMtpWeUoScZJ8mji472SddJSxFWSWilPJZfPqaQlFStkyTro2/6VdsYqtGrHEVJT9zYUoq6ze1nq1p6Ho1VcZPZe9/UzezfZFPA0XGGb1berdrZciXzDJkV2gjH7OtDEJ5PJj0jiYiotlZ57rPPqFh+13BeJbXC2RUPVLUhvE/g7KiwZ3+Zz4dtp/sP1Dfa0X+zJdUaL1EH8keOX0bE3wXqxME3w1fE5vbOLlUp2ozlSntxe1ZPJO7WafI2dmVPAlOaqTz2msrtvgPyRb6YcWh7pvkWoPl6D7IliwMuy0iIPEZR6iIyJbpjHJBWBixiGmAOyTZGWJslCN7QLHOIEkegzAU2KnMObMtWRz5JUWkNoSvOPmPxHeuezBRjFJNzmttS/LGKafVmLBS/Sw/i+x3JxH6eVpimqZysBSrR2lVnGp4m04wcLLha7+o/EVlFXeXmaqtkrnlsdXdabivci/6mvsT6nKsa62VjjyBxvaEqjtDJcePkIpUU9W2/M0xprQXs2Z5UuUncjo6XSCp0Irj63JVoJ5qxcwZSsDihWZp0lwKhCPkaWUlHgZPGWpiLcGmHHPUJUo7l8yrLy6goNDckxjguBTRIz4MFzLsii7WVyd3vd+hTmwtvoVaEJlRe4ZC6yIpl3tncVILCqNmecdp/2JVqNuy6j6cUkS/y6KXXZjlRzJ+GNbXK4Wm4SxIObMOxbIbCizRJ8S6buPgkw5MqGHXP6AVaK3a+Zo0Fxi2y3EVg4XGzpu0ruPHgd6nJNJrRo40qSY7sipst05ecfujTFNxdMmVNWjXjvc6owRkb8cvA/wCJHNRWV/kKGjVCQ+DMUGaKcgjIbNSLAiw7m6ZB12hNRGiRmqs7pMwRkrMw1ZGqvIxVGcOWRtFDOzX+mh/EekcLczzXZv62H8X2PTVJI19J7X/SMmzldu19im7avwrqcHCxsjo+0tXx04cnL7L6MwpZHJ6mXLK/0awVRLmuYErF3BlIyGC11BfMuc7E2rgMiKtyIkQAKc1bIBw+Yx6kfL0FQGeUbAW8x81cjgQ4lWZtpxyd+QW2946ML6kceRPBjsSqhdSV8kXVjloBfOL6E9p0VS2Op0rf5oO5luIKepulRm+y2wStqxcbvUABqyLwciqiyKwbs2S/cP4NUnd8i1yBYS5FkDICKktmcZcJJ9N41MTi1l0JlopHV7Qj4OqOZY6OJqp0lnm9h/I55pk7ZK6LiOgxSDgShmqDGXEQYy5smTR2ZVDNWmZnihNSuds5dGSRK8jJNhVKgps4MjNki4VXFqScU1mnJpRXm3kOXaVW7bxGHV9F3tDLys22cX2iqbOFrS4U5M8P7N/pZqfN2y0S1NsMlHFKVktXJI+hTxEqlTalLaaVr5WsuBojoc/Ca9DdFnFFuVt/JrJV0W2K1Gti9nO5ZIFcVSqbh9XUxaMiTplR7NUVwLbZUXdcGVJNalWIN21f/wALikLbCVr6BYFTefUk8y5pEa+gARPMtgvcHYBFzV1c50tTqP3Tkz94xydM0gdCNrJ6uwNy6EfCgtg1RDAsGnuKKTGBVQVR95jZIClrciW0NaND16BQBqyy9A6aNCQkhOKeTHGbFvIiWio7E4b3+jNyOfhF4ujN6KTtITVMItMEq4CNEZBbZl2yd4WmFGRY7mHHFXPM0sUdDD1rnTkmRFHaVQNMx0ZGmDONs0OZ7Xu2CxD/AHEzyvsPD9Htflj87s9R7YK+BxP/AEJnC9iKf/DRfGTX9ORc/wDg/wChH3nqaZqhIz0UOMYaHLY1MVB3v5lbRbjkXYhdaVn0Zl5jMRPcVhVd9DKTt0XFdWMozHsyuNmMjMqMvhiaGWyLUCIKFyuiQ4wVgFllwQ2KyJslCAUAbWDg9ShABVqXOW5eI69lY5VaymYZFo1xnSwecQpg4Syis+IFWSuarpEPZd7AzlYFyEValhSkNIZOY6ktDFSdzVCV7ERdsclSNT16FwFTeT8g4SyRsZhyMtd3HTloZqrJnoqOxeCl4rcmdBHNwK8XqdIIaCWyMCTCYqbGxAykDtgSYFxWOjx1CZ2cGzh4dZnbwSN8jJR2KDNkDHQNUTApnP8AapXwddfuZnC9gal8M4vWFSXpJJr7npO2FehVX7uX0OV7NUPBtaJvlnY3pPC/6SvedygNbBsQyWhsqqhUKtvezHAzpoUk/gEZKubHUVYpQsXtWJx41K7KlKtDpQujFNuOTNUKiKnSusxTi0wixdOuaIz4GV0eBdOEtyZCkyqR0VNWBnWSMdpvd8xUoyvm0W5v6FxRqhXVy5VVxMSofm9EX3SX7TfUnkxuKNdaulHKxzoxzu97HNrgErMUuxroJSKnMkKael+lzTGhG2aa53CmLoxykUqVzoU8HGzle9hdJeKyEot7G5JaMs47O6xeHqHK9p+2FSrQhfRpPqrv7GzCVlJKSKcHBoSakjq00U2LlVsstS6N3qaWiKYxXE10PuIr6hNdBHYnDP8ASJflbOgcnC1L135P6HVuEdCbtkbEzYyTEzYMaFSACkwDOyzyWHidrBo5dCB2MJE6poyR0qKNMRFFD0ZFC8bHapzXGEl8jP2VQUKUIrdCK62z+ZqqaMVhpDb6S/YkjRJ6eZaYDCT4B8gVUCvcCSCuHyAE1kJuPloY5TzIb4ysaVoZtD6E9xkaG0pGuRfJMTSy4oT3qDVSxnyRVDquhgbNWJqqxiZE2VFBZsuFHPcCrlqT3GZRuhhae9v1M9fZhPwrKyyYdChJvN2XzFYtJSyXDV3Lb6EtjHiVe6illuQyCcld5L6i6GKtlbokOqtyjeXhvot/UaJY3ARbUnuennxM9BWbby3HQwT8NuW452KdpSXMtLQm9nyX2ixkpYmq27vvJL0Z632LpVHT7yUm020k835nBh2V+I7QqweUVUnKT/Knp1yPomEoxilGKsksktxtmkqUSMa+Q6NIclYkUS+ZklRTdkAqxug2ypadAYI5WA/XW5TOxc4uDf8AxD/mOyAEkxMxrEzExoTJgXCmBcgqzi0aZ1MNARTpm6hE6pmaNNNDUBFBmQwKztF+QjC6sbi/cl5GTBVd4SX43+xrdHRsXBBRiDUVsgEVMkUSCG7IAJkYakczoVVZHPq8TKfbSLjoCcgsPLPzMzmMwsvEup0ZPaZrY6auwoRd7EqSz06h09nizi+TosfPDJLW/kIcMvI3U6WWrDnSTXDqa8OjPkc6FZoL8RxAzg7Mb36e5dEQuih+Fk5aK3N6GfERSm73a5jsPX3K7E4pPbzyvbTcU/aStmmliYpWS9FmwsRuvr8kgKVNZKK829wqc25NK75lW67FRvwOjOfjHeTfP7ju/cUcrE4q123s65vQXLSCtmLA0VCtWkt8lfm3n9kdugzlYezlUad7uD/7Tq0VkF3Jja/EbcoLUqKzNSC5PIXWlZDJoTjH4RS0NI4/Z8r4h+U/sd0832RO+I6TPSIBEYqY1iagmAibF3LqMVcVDsKETVSRCG0iR6CLIQMRjv1cv4TzlPtRUpxvZxd1JJ5rPKX+cSEOnBFSTTIk6PYwmpJNZ5btBc0QhzIoGKtn9xyLIMBGJrWWWf0OTUnfJ7uerIQF7kHwITJQqWkrcyELnoS2boVOKHU6Kej+RCHHFWzofRqjSkt6GU6iWtiENq46MdiMdHSS8mcTt72gp4Kl3tWnKac1G0FHau03va4EIKEU8qT+Rt/i2eeo/wCqWFX/ACMT0VL/ANyVf9UMJKV3RxVst1K//mUQ9H/Nj+jnWWR1vZ724oY2r+HpUq1N7EpXn3drRtfSTzzPVWjFEIcmeEYS6NccnLZiq73dWW96HzrtDtCtiK7Tpz/DqclLaipbWxF7MVDXXMhDLBVtl5X1R0/YbtFy8FSUnKV9lzWzJtP3WuJ7fDO7sQhWaCU+hQk3E17AE0QgASmrnO7crbFOT4LLzeRCEyGjzvs3UviEn8E39D2CLIUyUCxVQhBDMlQUQggP/9k="
                            class="card-img-top" alt="...">

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                @if(session()->get('data')->id == $p->author_id)
                                    <a href="/edit_post/{{ $p->id }}">chỉnh sửa bài viết</a>
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
                                        <input type="hidden" name="pid" value="{{ $p->id }}">
                                        <input type="text" name="subject" placeholder="chủ đề">
                                        <input type="text" name="details" placeholder="chi tiết">
                                        <input type="submit" value="báo cáo bài viết">
                                    </form>
                                </div>
                            </li>
                            <li class="list-group-item">Vestibulum at eros</li>
                        </ul>
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
