<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index(){
    	$contacts = Contact::all();
    	$response = array(
                    'status' => 'success',
                    'response' => $contacts
                );
    	return response()->json($response);
    }

    public function store(Request $request){
        if ($request->has('contact_id')) {
            $contact = Contact::find($request->contact_id);
        } else {
            $contact = new Contact();
        }
        $contact->name = $request->name;
    	$contact->email = $request->email;
    	$contact->address = $request->address;
    	
        if($contact->save()) {
            $message = '';
            if ($request->has('contact_id')) {
                $message = 'Contact updated successfully';
            }else{
                $message = 'Contact added successfully';
            }
    		$response = array(
                'status' => 'success',
                'message' => $message,
            );
    	} else {
    		$response = array(
                'status' => 'error',
            );
    	}
    	return response()->json($response);
    }

    public function edit($c_id)
    {
        $contact = Contact::find($c_id);
        $response = array(
            'status' => 'success',
            'contact' => $contact
        );
        return response()->json($response);
    }

    public function destroy($id)
    {
        $contact = Contact::find($id);
        if ($contact) {
            $deletContact = $contact->delete();
            if ($deletContact) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Contact deleted successfully'
                );
            } else {
                $response = array(
                    'status' => 'error',
                );
            }
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Contact not found'
            );
        }
        return response()->json($response);
    }
}
