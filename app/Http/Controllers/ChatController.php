<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;

class ChatController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }

    public function chat_whit(User $user){

        $user_one = auth()->user();
        $user_two = $user;

        $chat = $user_one->chats()->wherehas('users', function ($query) use ($user_two){
            $query->where('chat_user.user_id', $user_two->id);
        })->first();


        if(!$chat){
            $chat = \App\Models\Chat::create([]);//la sala de chat no tiene parametros , solo el id
            $chat->users()->sync([$user_one->id, $user_two->id]);

        }


        return redirect()->route('chat.show', $chat);





    }

    public function show(Chat $chat){

        abort_unless($chat->users->contains(auth()->id()), 403);
        return view('chat',[
            'chat' => $chat
        ]);
    }
}
