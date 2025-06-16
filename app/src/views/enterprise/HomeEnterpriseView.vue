<template>
<section class="min-h-screen flex flex-col">
    <NavBarComponent />

    <section class="w-full max-w-7xl mx-auto p-5">
        <BreadCrumbComponent :breadcrumb="loadBreadCrumb()" />

        <div class="flex flex-row">
            <router-link to="/empresa/conta" class="md:w-[32%] m-[1%] bg-green-50 rounded-md p-2 hover:shadow-md border border-l-4 border-l-green-700 cursor-pointer">
                <h3 class="text-md font-medium">Gerenciar Minha Conta</h3>
                <p class="text-xs mt-1">Altere os dados do seu perfil e consulte diversas estatísticas!</p>
            </router-link>
            <router-link to="/empresa/servicos" class="md:w-[32%] m-[1%] bg-green-50 rounded-md p-2 hover:shadow-md border border-l-4 border-l-green-700 cursor-pointer">
                <h3 class="text-md font-medium">Gerenciar Meus Serviços</h3>
                <p class="text-xs mt-1">Cadastre e gerencie suas empresas, serviços, estabelecimentos, locais e divulgue para serem avaliados pelos seus clientes!</p>
            </router-link>
            <router-link to="/empresa/avaliacoes" class="md:w-[32%] m-[1%] bg-green-50 rounded-md p-2 hover:shadow-md border border-l-4 border-l-green-700 cursor-pointer">
                <h3 class="text-md font-medium">Gerenciar Avaliações</h3>
                <p class="text-xs mt-1">Consulte e responda a avaliações, veja os feedbacks dos seus clientes e analise como anda sua reputação!</p>
            </router-link>
        </div>
    </section>

</section>
</template>

<script>
import NavBarComponent from '@/components/Home/NavBarComponent.vue';
import BreadCrumbComponent from '@/components/painel/BreadCrumbComponent.vue';

export default {
    name: 'HomeEnterpriseView',

    components: {
        NavBarComponent,
        BreadCrumbComponent
    },

    methods: {
        loadBreadCrumb: function () {
            return [];
        }
    }
}
</script>
