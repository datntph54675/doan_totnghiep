<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Tour;
use App\Mail\ContactFormMail;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        $stats = [
            'tours'     => Tour::where('status', 'active')->count(),
            'customers' => \App\Models\Customer::count(),
            'years'     => 5,
        ];

        return view('pages.about', compact('stats'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'fullname' => ['required', 'string', 'max:120'],
            'email'    => ['required', 'email', 'max:255'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'subject'  => ['required', 'string', 'max:200'],
            'message'  => ['required', 'string', 'max:5000'],
        ], [
            'fullname.required' => 'Vui lòng nhập họ tên.',
            'email.required'    => 'Vui lòng nhập email.',
            'email.email'       => 'Email không hợp lệ.',
            'subject.required'  => 'Vui lòng nhập tiêu đề.',
            'message.required'  => 'Vui lòng nhập nội dung tin nhắn.',
        ]);

        // Gửi email
        $to = env('MAIL_TO', 'admin@gotour.vn');
        try {
            Mail::to($to)->send(new ContactFormMail($validated));
        } catch (\Exception $e) {
            Log::error('Contact form mail fail: ' . $e->getMessage(), ['payload' => $validated]);
            return redirect()
                ->route('contact')
                ->with('error', 'Gửi mail thất bại: ' . $e->getMessage());
        }

        return redirect()
            ->route('contact')
            ->with('success', 'Cảm ơn bạn, ' . $validated['fullname'] . '! Chúng tôi đã nhận tin nhắn và sẽ phản hồi trong thời gian sớm nhất.');
    }
}
