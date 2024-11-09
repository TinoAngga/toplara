@component('mail::message')
# Introduction

The body of your message.

@component('mail::table')
<p class="p">
    - <b>Invoice:</b> {{ '#1' }}<br />
    - <b>Pembayaran:</b> {{ 'TEST' }}<br />
</p>
@endcomponent
@component('mail::button', ['url' => '', 'color' => 'success'])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
