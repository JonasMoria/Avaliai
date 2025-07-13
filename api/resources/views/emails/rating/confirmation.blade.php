@component('mail::message')
# Obrigado pela sua avaliação!

Você avaliou o serviço **{{ $rating->enterpriseService->name }}**
da instituição **{{ $rating->enterpriseService->enterprise->name }}**
com {{ $rating->stars }} estrela{{ $rating->stars > 1 ? 's' : '' }}.

**Comentário enviado:**
> {{ $rating->comment }}

Sua opinião é muito importante para a comunidade! 💬

@endcomponent
