@component('mail::message')
# Olá, {{ $userName }}!

Recentemente seu e-mail de acesso a nossa plataforma foi alterado para <b>{{ $userNewEmail }}</b>.

Caso não foi você o responsável pela alteração, por favor, entre em contato com nosso suporte!

Obrigado,<br>
Equipe Avaliai
@endcomponent