@component('mail::message')
# New Contact Form Submission

You have received a new message through your portfolio contact form.

**From:** {{ $contactData['name'] }}  
**Email:** {{ $contactData['email'] }}  
**Subject:** {{ $contactData['subject'] }}

## Message:
{{ $contactData['message'] }}

---

@component('mail::button', ['url' => 'mailto:' . $contactData['email']])
Reply to {{ $contactData['name'] }}
@endcomponent

Thanks,<br>
{{ config('app.name') }} Portfolio System
@endcomponent