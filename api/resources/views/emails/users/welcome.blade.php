@component('mail::message')
# Olá, {{ $user->name }}!

Seja bem-vindo(a) ao Avalia Ai!. Seu cadastro como usuário foi realizado com sucesso!
Agora você pode avaliar suas experiências a vontade!

@component('mail::button', ['url' => config('app.url')])
Acessar plataforma
@endcomponent

Obrigado,<br>
Equipe Avalia Ai!
@endcomponent
