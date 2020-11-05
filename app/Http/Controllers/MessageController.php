<?php

namespace App\Http\Controllers;

use App\Events\MessageSend;
use App\Message;
use App\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function userList()
    {
      if(\Request::ajax()){
        $userList = User::latest()->where('id','!=',auth()->user()->id)->get();
        return response()->json([
          'data'=>$userList,
          'success'=>true,
        ]);
      }else{
        abort(404);
      }

    }

    public function userMessage($id=null)
    {
      if(\Request::ajax()){
        $user= User::findOrFail($id);
        $userMessage= $this->message_by_user_id($id);
        return response()->json([
          'messages'=>$userMessage,
          'user'=>$user,
          'success'=>true,
        ]);
      }else{
        abort(404);
      }

    }

public function sendMessage(Request $request){
  if(!\Request::ajax()){
   abort(404);
  }

  $messages = Message::create([
  'from'=>auth()->user()->id,
  'to'=>$request->user_id,
  'message'=>$request->message,
  'type'=>1
  ]);

  broadcast(new MessageSend($messages));
  return response()->json($messages,200);
  }

public function DeleteSingleMessage($id){
  $message = Message::findorFail($id);
  $message->delete();
  return response()->json([
    'data'=>$message,
    'success'=>true,
    'message'=>'message deleted successfully',
  ]);
}

public function DeleteMultipleMessage($id=null){

$messages = $this->message_by_user_id($id);

  foreach ($messages as $message){
    Message::findOrfail($message->id)->delete();
  }
  return response()->json([
    'message'=>'Deleted all message',
  ]);
}


public function message_by_user_id($id){
  $messages = Message::where(function($q) use($id){
    $q->where('from',auth()->user()->id);
    $q->where('to',$id);
    // $q->where('type',0);
  })->orWhere(function($q) use($id){
    $q->where('from',$id);
    $q->where('to',auth()->user()->id);
    // $q->where('type',1);
  })->with('user')->get();
  return $messages;
}

}



