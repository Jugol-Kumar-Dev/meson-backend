<?php


namespace App\Http\Controllers;


use App\Events\SendMessageEvent;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ChatController extends Controller
{

    public function index(){
        return inertia('Chat/Index');
    }

    public function create()
    {
        return view('chat.create');
    }



    public function users(){
        if (!Request::ajax()){
            abort(404);
        };

        $student = Request::input('is_student');
        $search = Request::input('search');


        $users = [];
        if ($student == 'true'){
            $users = User::with(["chats" => function($item){
                return $item->latest()->first();
            }])->where('id', '!=', Auth::id())
                ->where('role', 'admin')
                ->with("chats", function ($item){
                    $item->latest()->first();
                })->withCount(['chats', 'chats as chat_count' => function($q){
                    return $q->where('is_seen', 0);
                }])->get();
        }else{
            if ($search != 'null'){
                $users = User::query()->with(["chats" => function($item){
                    return $item->latest()->first();
                }])->where('id', '!=', Auth::id())->when(Request::input('search'), function ($query, $search){
                    $query->where('name', 'like', "%{$search}%")->where('id', '!=', Auth::id());
                })->withCount(['chats', 'chats as chat_count' => function($q){
                    return $q->where('is_seen', 0);
                }])->get();
            }else{
                $users = User::with(["chats" => function($item){
                    return $item->latest()->first();
                }])->where('id', '!=', Auth::id())->with("chats", function ($item){
                    $item->latest()->first();
                })->withCount(['chats', 'chats as chat_count' => function($q){
                    return $q->where('is_seen', 0);
                }])->get();
            }
        }

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

    }

}
