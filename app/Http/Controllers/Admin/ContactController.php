<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query();

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('fullname', 'like', '%' . $request->keyword . '%')
                  ->orWhere('email', 'like', '%' . $request->keyword . '%')
                  ->orWhere('subject', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $contacts = $query->latest()->paginate(15)->withQueryString();

        return view('admin.contacts.index', compact('contacts'));
    }

    public function show(Contact $contact)
    {
        return view('admin.contacts.show', compact('contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,replied,closed'],
        ]);

        $newStatus = $validated['status'];
        $currentStatus = $contact->status;

        // Validate trạng thái chuyển đổi
        if ($currentStatus === 'pending' && !in_array($newStatus, ['replied'])) {
            return redirect()->back()->with('error', 'Từ trạng thái "Chờ xử lý" chỉ có thể chuyển sang "Đã trả lời".');
        }

        if ($currentStatus === 'replied' && !in_array($newStatus, ['closed'])) {
            return redirect()->back()->with('error', 'Từ trạng thái "Đã trả lời" chỉ có thể chuyển sang "Đã đóng".');
        }

        if ($currentStatus === 'closed') {
            return redirect()->back()->with('error', 'Liên hệ đã đóng không thể thay đổi trạng thái.');
        }

        // Nếu chuyển sang replied, set replied_at
        $updateData = ['status' => $newStatus];
        if ($newStatus === 'replied' && $currentStatus !== 'replied') {
            $updateData['replied_at'] = now();
        }

        $contact->update($updateData);

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Cập nhật trạng thái liên hệ thành công.');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Xóa liên hệ thành công.');
    }
}
