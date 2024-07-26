<?php

namespace App\Http\Controllers;

use App\Events\SendMessageEvent;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class StudentChatController extends Controller
{

    public function index(){
        return inertia('Student/Chat/Index');
    }

    public function create()
    {
        return view('chat.create');
    }



    public function users(){
        if (!Request::ajax()){
            abort(404);
        };




        return response()->json($users, 200);
    }


    public function user($id){
        if (!Request::ajax()){
            abort(404);
        };

        $user = User::findOrFail($id);
        Chat::Where('form_id', $id)
            ->where('to_id', Auth::id())
            ->where('is_seen', 0)
            ->update(['is_seen' => 1]);

        $chat = Chat::with('user')->where(function($q) use($id){
            $q->where('form_id', Auth::id());
            $q->where('to_id', $id);
        })->orWhere(function($q) use($id){
            $q->where('form_id', $id);
            $q->where('to_id', Auth::id());
        })->get();

        return response()->json(["user"=>$user, "chats" => $chat], 200);
    }


    public function sendMessage(){
//        if (!Request::ajax()){
//            abort(404);
//        };

//        return Request::all();

        $data = Request::all()['sendData'];
        $user = User::findOrFail(Auth::id());
        $chat = Chat::create([
            'uuid'    => random_int(1111111111, 9999999999),
            'form_id' => Auth::id(),
            'to_id'   =>  $data['to_id'],
            'message' => $data['message']
        ]);

        event(
            (new SendMessageEvent($chat, $user))->dontBroadcastToCurrentUser()
        );

        return $chat;

//           SendMessageEvent::dispatch($message);

    }}
