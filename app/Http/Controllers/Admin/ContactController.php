<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\AuditsAdminActions;
use App\Http\Controllers\Controller;
use App\Models\Contact;

class ContactController extends Controller
{
    use AuditsAdminActions;

    public function contacts()
    {
        $this->authorize('viewAny', Contact::class);

        $contacts = Contact::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.contacts', compact('contacts'));
    }

    public function contact_delete($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return redirect()->route('admin.contacts')->with('error', 'Contact not found.');
        }

        $this->authorize('delete', $contact);
        $contact->delete();
        $this->auditAdminAction('contact.deleted', Contact::class, $id);

        return redirect()->route('admin.contacts')->with('success', 'Contact deleted successfully!');
    }
}
