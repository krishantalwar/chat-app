<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Firestore\FirestoreClient;
use App\Models\ChatRoomMembers;
use App\Models\ChatRooms;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ChatNotification;
use Illuminate\Support\Facades\Notification as FacadeNotification;

class FirebaseController extends Controller
{
    //
    public function update_chat(Request $request)
    {
        $message = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $request->message);
        // dd(strip_tags($message));
        $message = strip_tags($message);
        if (!empty($message)) {
            $room_id = $this->FindChatRoomId($request->all());

            // $this->newMesgNotificationCreate($data);

            // dd("asd");
            $this->createdChatRoomID($room_id, $request->user_id);
            $this->updateChatInFireStore($room_id, $message, $request->all());
            $data = [
                "title" => $request->user_name,
                "message" => $message,
                "user_id" => $request->user_id,
                "chat_id" => $room_id,
            ];
            $this->newMesgNotificationCreate($data);
            return $this->DynamicChatsection($room_id);
        } else {
            return $response = [
                'document' => "",
                'message' => 'Please enter valid Message',
                'type' => 'error',
            ];
        }
        die;
    }


    public function newMesgNotificationCreate($data)
    {
        $message = collect([
            "title" => $data['title'],
            "message" => $data['message'],
            "status" => 0,
            "from_user_id" => auth()->user()->id,
            "user_id" => $data['user_id'],
            "chat_id" => $data['chat_id'],
        ]);
        $users = User::find($data['user_id']);
        FacadeNotification::send($users, new ChatNotification($message));
    }

    public function FindChatRoomId($data)
    {

        $auth_id = auth()->user()->id;
        $second_user = $data['user_id'];
        if (ord(strtolower((string)$auth_id)) > ord(strtolower((string)$second_user))) {
            $chatroom_id = $auth_id . "user" . $second_user;
        } else {
            $chatroom_id = $second_user . "user" . $auth_id;
        }
        return $chatroom_id;
    }

    public function FindChatRoomIdForApi($user_id)
    {

        $auth_id = auth()->user()->id;
        $second_user = $user_id;
        if (ord(strtolower((string)$auth_id)) > ord(strtolower((string)$second_user))) {
            $chatroom_id = $auth_id . "user" . $second_user;
        } else {
            $chatroom_id = $second_user . "user" . $auth_id;
        }
        return $chatroom_id;
    }

    public function createdChatRoomID($chatroom_id, $second_user)
    {
        $auth_id = auth()->user()->id;
        $result = ChatRooms::where("chat_room", $chatroom_id)->first();
        if (empty($result)) {
            $chtaroom = ChatRooms::create([
                'chat_room' => $chatroom_id,
                "created_by_user" => auth()->user()->id
            ]);
            ChatRoomMembers::insert([
                [
                    'chat_rooms_id' => $chtaroom->id,
                    'member_id' => auth()->user()->id,
                    'created_at' => Carbon::now(),
                    'updated_at' =>  Carbon::now()
                ],
                [
                    'chat_rooms_id' => $chtaroom->id,
                    'member_id' => $second_user,
                    'created_at' => Carbon::now(),
                    'updated_at' =>  Carbon::now()
                ]

            ]);
        }

        return $chatroom_id;
    }


    public function updateChatInFireStore($room_id, $messageText, $data)
    {
        $serviceAccount = base_path() . config('firebase.credentials.file');
        $db = new FirestoreClient([
            'keyFilePath' => $serviceAccount,
            'projectId' => 'chat-ef87f',
        ]);
        $currentDateTime = round(microtime(true) * 1000);
        $chatRef = $db->collection('chat');
        $curTimestamp = $currentDateTime;
        $chatRef->document('room_' . $room_id)
            ->collection('messages')
            ->document('msg_' . $curTimestamp)
            ->set([
                'chat_id' => $room_id,
                'id' => (int)  $curTimestamp,
                'is_read' => 0,
                'message_text' => $messageText,
                'sender_id' => (string) Auth::id(),
                'sender_name' => (string) auth()->user()->name,
                'receiver_id' => (string) $data['user_id'],
                'receiver_name' => (string) $data['user_name'],
                'sent_at' => (int) $curTimestamp,
            ]);

        $getActionMessages = $chatRef->document('room_' . $room_id)->collection('messages');
        $query = $getActionMessages->where('chat_id', '=', $room_id);
        $documents = $query->documents();
        $ChatMessages = [];
        foreach ($documents as $document) {
            if ($document->exists()) {
                $ChatMessages[] = $document->data();
            }
        }
        return $this->returnResponse(HTTP_STATUS_OK, true, $ChatMessages, $ChatMessages);
    }


    public function loadUserChatViewDynamicChatsection(Request $request)
    {
        $data['user_id'] = $request->user_id;
        $room_id = $this->FindChatRoomId($data);
        return $this->DynamicChatsection($room_id);
    }


    public function DynamicChatsection($room_id)
    {
        $serviceAccount = base_path() . config('firebase.credentials.file');
        $db = new FirestoreClient([
            'keyFilePath' => $serviceAccount,
            'projectId' => 'chat-ef87f',
        ]);
        // dd($db);
        $chatRef = $db->collection('chat');
        $getActionMessages = $chatRef->document('room_' . $room_id)->collection('messages');
        $query = $getActionMessages->where('chat_id', '=', $room_id);
        $documents = $query->documents();
        $ChatMessages = [];
        foreach ($documents as $document) {
            if ($document->exists()) {
                $ChatMessages[] = $document->data();
            }
        }

        $appendData = view('firebase-dynamic-chat', compact('ChatMessages'))->render();

        return $response = [
            'document' => $appendData,
            "chat_id" => $room_id,
            'message' => 'Message Sent Successfully',
            'type' => 'success',
        ];
    }
}