@extends('layout.app')
@section('content')
<section style="background-color: #eee;">
    <div class="container py-5">

        <div class="row">

            <div class="col-md-6 col-lg-5 col-xl-4 mb-4 mb-md-0">

                <h5 class="font-weight-bold mb-3 text-center text-lg-start">Member</h5>

                <div class="card">
                    <div class="card-body">

                        <ul class="list-unstyled mb-0">
                            @foreach($users as $user)
                            <li class="p-2 border-bottom userlist" style="background-color: #eee;" id="{{$user->id}}"
                                data-name="{{$user->name}}">
                                <a href="#!" class="d-flex justify-content-between">
                                    <div class="d-flex flex-row">
                                        <img src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/avatar-8.webp"
                                            alt="avatar"
                                            class="rounded-circle d-flex align-self-center me-3 shadow-1-strong"
                                            width="60">
                                        <div class="pt-1">
                                            <p class="fw-bold mb-0">{{$user->name}}</p>

                                        </div>
                                    </div>
                                </a>
                            </li>
                            @endforeach
                        </ul>

                    </div>
                </div>

            </div>

            <div class="col-md-6 col-lg-7 col-xl-8 bg-white">

                <div id="mainChatSection">



                </div>

            </div>

        </div>

    </div>
</section>


@endsection

@section('footer_scripts');
<script>
var now = new Date();
var time = now.getTime();
time += 10800 * 1000;
now.setTime(time);

// moment.tz.guess();
var current_timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
var current_timezone = current_timezone.replace("Calcutta", "Kolkata");
document.cookie = 'user_timezone=' + current_timezone + '; expires=' + now.toUTCString() + ';path=/';




$(".userlist").click(function() {
    // console.log($(this).attr('id'));
    $(".userlist").removeClass("activeuser");
    $(this).addClass("activeuser");
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: '/get_user_chat',
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            user_id: $(this).attr('id'),
        },
        dataType: 'JSON',
        success: function(data) {
            if (data.type == 'success') {
                var message = data.message;
                var d = $("#mainChatSection");
                d.addClass(data.chat_id);
                d.html(data.document)
            } else {


            }
        }
    });
})


$(document).on("click", ".send", function() {
    let msg = $("#textAreaExample2").val().toString();

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: '/edit_user_chat',
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            user_id: $(".activeuser").attr("id"),
            user_name: $(".activeuser").data("name"),
            message: msg,
        },
        dataType: 'JSON',
        success: function(data) {

            if (data.type == 'success') {
                var message = data.message;

                var d = $("#mainChatSection");
                d.addClass(data.chat_id);
                d.html(data.document)

            } else {


            }
        }
    });

});
</script>
@endsection