<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\Message;

class MessageController extends Controller
{
    //
    public function index(): View
    {
        $messages = Message::orderBy('id')->get();
        return view('message/index', ['messages' => $messages]);
    }

    public function store(Request $request): RedirectResponse
    {
        $message = new Message();
        $message->body = $request->body;
        $message->save();

        return redirect('/messages');
    }

    public function destroy(string $id): RedirectResponse
    {
        DB::delete('delete from messages where id =' . $id);

        return redirect(route('message.index'));
    }
}
