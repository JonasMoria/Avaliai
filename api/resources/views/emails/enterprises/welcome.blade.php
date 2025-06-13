@component('mail::message')
# Olá, {{ $enterprise->name }}!

Seja bem-vindo(a) ao Avalia Ai!. Seu cadastro como empresa foi realizado com sucesso!
Agora você pode receber e comparar feedbacks para melhor gerenciar sua empresa/organização!

@component('mail::button', ['url' => config('app.url')])
Acessar plataforma
@endcomponent

Obrigado,<br>
Equipe Avaliai
@endcomponent
