<template>
<section class="min-h-screen flex flex-col bg-gray-50">
    <NavBarComponent />

    <section class="w-full max-w-7xl mx-auto p-5">
        <BreadCrumbComponent :breadcrumb="loadBreadCrumb()" />

        <button @click="toggleModalRegisterService()" type="button" class="bg-green-400 p-2 rounded-md pl-4 pr-4 text-sm text-white font-bold hover:shadow-lg">Cadastrar</button>
    </section>
    <NewServiceModal :modal-settings="modalNewService" @putServiceInList="putService"/>
</section>

</template>

<script>
import NavBarComponent from '@/components/Home/NavBarComponent.vue';
import BreadCrumbComponent from '@/components/painel/BreadCrumbComponent.vue';
import NewServiceModal from '@/components/painel/modals/NewServiceModal.vue';

export default {
    name: 'ServicesView',

    components: {
        NavBarComponent,
        BreadCrumbComponent,
        NewServiceModal
    },

    data() {
        return {
            modalNewService: {
                show: false
            },

            servicesList: [],
        }
    },

    methods: {
        loadBreadCrumb: function () {
            return [{
                name: 'Meus Serviços',
                path: '/empresa/servicos'
            }, ];
        },

        putService: function(service) {
            this.servicesList.push(service);
        },

        toggleModalRegisterService: function() {
            this.modalNewService.show = !this.modalNewService.show;
        }
    }
}
</script>
