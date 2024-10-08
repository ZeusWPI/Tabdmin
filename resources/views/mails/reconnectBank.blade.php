@component('mail::message')

# Hallo,

De connectie met je bank is vervallen of vervalt morgen.
Verbind je bank opnieuw om je transacties automatisch te blijven verwerken.

@component('mail::button', ['url' => $url])
Verbind Account
@endcomponent

Bedankt

@endcomponent
