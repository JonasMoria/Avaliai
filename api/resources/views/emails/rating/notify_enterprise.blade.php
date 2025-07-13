@component('mail::message')
# Nova avaliação recebida!

Um usuário avaliou seu serviço com **{{ $rating->stars }} estrela{{ $rating->stars > 1 ? 's' : '' }}**.

**Comentário:**
> {{ $rating->comment }}

Fique de olho na sua reputação!

@endcomponent
