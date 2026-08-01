<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    function index(){
        $this->authorize('view contacts');

        $contacts = Contact::latest()->get();
        return view('backend.contact.index', compact( 'contacts'));
    }
}
