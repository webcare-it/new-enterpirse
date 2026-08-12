<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index()
    {
        $contact_messages = \App\Models\Admin\ContactMessage::latest()->get();
        return view('backend.contact_message.index', compact('contact_messages'));
    }

    public function show(\App\Models\Admin\ContactMessage $contact_message)
    {
        if ($contact_message->status == 'unread') {
            $contact_message->status = 'read';
            $contact_message->save();
        }
        return view('backend.contact_message.show', compact('contact_message'));
    }

    public function destroy($id)
    {
        try {
            $contact_message = \App\Models\Admin\ContactMessage::findOrFail($id);
            $contact_message->delete();
            return redirect()->route('contact-message.index')->with('success', 'Contact message deleted successfully');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
