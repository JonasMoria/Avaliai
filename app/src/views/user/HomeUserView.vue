<template>
<section class="min-h-screen flex flex-col bg-gray-50">
    <NavBarComponent />

    <section class="w-full max-w-7xl mx-auto p-5">
        <BreadCrumbComponent :breadcrumb="loadBreadCrumb()" />

        <div class="flex flex-wrap">
            <router-link to="/usuario/conta" class="md:w-[31.3%] m-[1%] h-24 bg-green-50 rounded-md p-2 hover:shadow-md border border-l-4 border-l-green-700 cursor-pointer">
                <h3 class="text-md font-medium">Gerenciar Minha Conta</h3>
                <p class="text-xs mt-1">Altere os dados do seu perfil e consulte diversas estatísticas!</p>
            </router-link>
            <router-link to="/usuario/avaliacoes" class="md:w-[31.3%] m-[1%] h-24 bg-green-50 rounded-md p-2 hover:shadow-md border border-l-4 border-l-green-700 cursor-pointer">
                <h3 class="text-md font-medium">Gerenciar Minhas Avaliações</h3>
                <p class="text-xs mt-1">Veja as últimas avaliações postadas por você!</p>
            </router-link>
        </div>
    </section>
</section>
</template>

<script>
import NavBarComponent from '@/components/Home/NavBarComponent.vue';
import BreadCrumbComponent from '@/components/painel/BreadCrumbComponent.vue';

export default {
    name: 'HomeUserView',
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
