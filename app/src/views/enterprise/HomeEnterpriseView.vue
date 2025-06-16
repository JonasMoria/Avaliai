<template>
<section class="min-h-screen flex flex-col">
    <NavBarComponent />

    <section class="w-full max-w-7xl mx-auto p-5">
        <BreadCrumbComponent :breadcrumb="loadBreadCrumb()" />

        <div class="md:flex md:flex-row">
            <div class="md:w-[32%] m-[1%] bg-green-50 rounded-md p-2 hover:shadow-md border border-l-4 border-l-green-700 cursor-pointer">
                <h3 class="text-md font-medium">Gerenciar minha conta</h3>
            </div>
            <div class="md:w-[32%] m-[1%] bg-green-50 rounded-md p-2 hover:shadow-md border border-l-4 border-l-green-700 cursor-pointer">
                <h3 class="text-md font-medium">Gerenciar Meus Serviços</h3>
            </div>
            <div class="md:w-[32%] m-[1%] bg-green-50 rounded-md p-2 hover:shadow-md border border-l-4 border-l-green-700 cursor-pointer">
                <h3 class="text-md font-medium">Gerenciar Avaliações</h3>
            </div>
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
