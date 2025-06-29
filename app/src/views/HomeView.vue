<template>
<section class="min-h-screen flex flex-col">
    <NavBarComponent />

    <section class="flex-grow">
        <section class="flex-grow">
            <div class="relative bg-green-300">
                <div class="h-56 p-4 flex flex-col justify-center items-center">
                    <h3 class="text-3xl font-bold text-gray-600 text-center">
                        Descubra o melhor do mercado com o Avalia Ai!
                    </h3>
                    <h6 class="mt-2 text-xs text-gray-700 text-center font-semibold">
                        Encontre empresas incríveis, serviços de qualidade e confira avaliações reais do público — tudo ao seu alcance!
                    </h6>
                </div>
                <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-[0] h-20">
                    <svg class="relative block w-full h-[100px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
                        <path fill="#fff" d="M0,160L48,149.3C96,139,192,117,288,128C384,139,480,181,576,181.3C672,181,768,139,864,117.3C960,96,1056,96,1152,128C1248,160,1344,224,1392,256L1440,288L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                    </svg>
                </div>
            </div>
        </section>

        <div class="w-full mt-4 p-4 flex flex-col justify-center items-center">
            <h3 class="text-2xl font-bold text-gray-600 text-center">Explore as funcionalidades que vão revolucionar sua busca!</h3>
            <h6 class="mt-2 text-sm text-gray-700 text-center">Conheça as ferramentas inteligentes que facilitam sua escolha e tornam sua experiência única.</h6>

            <div class="md:grid md:grid-cols-2 gap-4 mt-10">
                <div class="border border-green-400 w-80 pt-2 pb-2 rounded-md shadow-lg flex flex-wrap">
                    <h2 class="w-full text-center font-semibold text-green-800 mb-3">Para empresas</h2>
                    <div class="w-4/12">
                        <img src="@/assets/icons/company.svg" alt="empresa_logo" class="border rounded-full h-24 p-2 shadow-md">
                    </div>
                    <div class="w-8/12">
                        <ul class="text-left ml-2 text-gray-500 text-xs">
                            <li class="flex items-center space-x-3 rtl:space-x-reverse">
                                <svg class="shrink-0 w-3.5 h-3.5 text-green-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                                </svg>
                                <span>Cadastre seus serviços</span>
                            </li>
                            <li class="mt-2 flex items-center space-x-3 rtl:space-x-reverse">
                                <svg class="shrink-0 w-3.5 h-3.5 text-green-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                                </svg>
                                <span>Receba diversas avaliações!</span>
                            </li>
                            <li class="mt-2 flex items-center space-x-3 rtl:space-x-reverse">
                                <svg class="shrink-0 w-3.5 h-3.5 text-green-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                                </svg>
                                <span>Ouça o que seus clientes tem a dizer e adote estratégias para alavancar seu negócio!</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="mt-10 md:mt-0 border border-green-400 w-80 p-3 rounded-md shadow-lg flex flex-wrap">
                    <h2 class="w-full text-center font-semibold text-green-800 mb-3">Para Usuários</h2>
                    <div class="w-4/12">
                        <img src="@/assets/icons/person.svg" alt="usuario_logo" class="border rounded-full h-24 p-2 shadow-md">
                    </div>
                    <div class="w-8/12">
                        <ul class="text-left ml-2 text-gray-500 text-xs">
                            <li class="flex items-center space-x-3 rtl:space-x-reverse">
                                <svg class="shrink-0 w-3.5 h-3.5 text-green-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                                </svg>
                                <span>Faça suas avaliações e conte sua experiência!</span>
                            </li>
                            <li class="mt-2 flex items-center space-x-3 rtl:space-x-reverse">
                                <svg class="shrink-0 w-3.5 h-3.5 text-green-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                                </svg>
                                <span>Acesse diversas avaliações e veja a reputação de diversas empresas e serviços!</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <FooterComponent />
</section>
</template>

<script>
import NavBarComponent from '@/components/Home/NavBarComponent.vue';
import FooterComponent from '@/components/Home/FooterComponent.vue';

export default {
    name: 'HomeView',

    components: {
        NavBarComponent,
        FooterComponent
    }
}
</script>
