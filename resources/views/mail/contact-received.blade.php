<x-mail::message>
# Contact request

**Name:** {{ $contactRequest->name }}  
@if ($contactRequest->organization)
**Organisation:** {{ $contactRequest->organization }}<br>
@endif
**Email:** {{ $contactRequest->email }}  
**Locale:** {{ $contactRequest->locale }}  
@if ($contactRequest->phone)
**Phone:** {{ $contactRequest->phone }}
@endif

{{ $contactRequest->message }}
</x-mail::message>
