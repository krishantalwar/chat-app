<ul class="list-unstyled">
    @if(isset($ChatMessages) && !empty($ChatMessages))
    @foreach($ChatMessages as $index => $value)
    @php

    $timeZone = !empty($_COOKIE['user_timezone'])?$_COOKIE['user_timezone']:date_default_timezone_get();
    $document = $ChatMessages[$index];
    $message = $document['message_text'];
    $sender_id=$document['sender_id'];

    $sentAt = $document['sent_at']; //in this i am getting 1648464900000
    $date= date('Y-m-d H:i:s', ($sentAt/1000));
    $l10nDate = new DateTime($date, new DateTimeZone('UTC'));
    $l10nDate->setTimeZone(new DateTimeZone($timeZone));
    $sentDateTime= $l10nDate->format('d M, g:i A');
    $auth_id=auth()->user()->id;
    @endphp
    @if($auth_id==$sender_id)
    <li class="d-flex justify-content-between mb-4">
        <div class="card w-100">
            <div class="card-header d-flex justify-content-between p-3">
                <p class="fw-bold mb-0">{{$document['sender_name'] }}</p>
                <p class="text-muted small mb-0"><i class="far fa-clock"></i> {{$sentDateTime}}</p>
            </div>
            <div class="card-body">
                <p class="mb-0">
                    {{ isset($message) ? $message : '' }}
                </p>
            </div>
        </div>
        <img src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/avatar-5.webp" alt="avatar"
            class="rounded-circle d-flex align-self-start ms-3 shadow-1-strong" width="60">
    </li>
    @else
    <li class="d-flex justify-content-between mb-4">
        <img src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/avatar-6.webp" alt="avatar"
            class="rounded-circle d-flex align-self-start me-3 shadow-1-strong" width="60">
        <div class="card w-100">
            <div class="card-header d-flex justify-content-between p-3">
                <p class="fw-bold mb-0">{{$document['sender_name'] }}</p>
                <p class="text-muted small mb-0"><i class="far fa-clock"></i> {{$sentDateTime}}</p>
            </div>
            <div class="card-body">
                <p class="mb-0">
                    {{ isset($message) ? $message : '' }}
                </p>
            </div>
        </div>
    </li>

    @endif


    @endforeach

    @else

    <div class=" container text-center mx-auto my-auto justify-content-center align-middle">
        <p>no chat found</p>
    </div>

    @endif
    <li class="bg-white mb-3">
        <div class="form-outline">
            <textarea class="form-control" id="textAreaExample2" rows="4"></textarea>
            <label class="form-label" for="textAreaExample2">Message</label>
        </div>
    </li>


    <button type="button" class="btn btn-info btn-rounded float-end send">Send</button>
</ul>