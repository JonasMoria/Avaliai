<template>
<section class="min-h-screen flex flex-col bg-gray-50">
    <NavBarComponent />

    <section class="w-full max-w-7xl mx-auto p-5">
        <BreadCrumbComponent :breadcrumb="loadBreadCrumb()" />
    </section>
</section>
</template>

<script>
import NavBarComponent from '@/components/Home/NavBarComponent.vue';
import BreadCrumbComponent from '@/components/painel/BreadCrumbComponent.vue';

export default {
    name: 'UserAccountView',
    components: {
        NavBarComponent,
        BreadCrumbComponent
    },

    methods: {
        loadBreadCrumb: function () {
            return [{
                name: 'Minha Conta',
                path: '/usuario/conta'
            }];
        }
    }
}
</script>
