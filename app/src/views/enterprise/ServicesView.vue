<template>
<section class="min-h-screen flex flex-col bg-gray-50">
    <NavBarComponent />

    <section class="w-full max-w-7xl mx-auto p-5">
        <BreadCrumbComponent :breadcrumb="loadBreadCrumb()" />
        <button @click="toggleModalRegisterService()" type="button" class="bg-green-400 p-2 rounded-md pl-4 pr-4 text-sm text-white font-bold hover:shadow-lg">Cadastrar</button>

        <div class="flex flex-wrap w-full mt-5">
            <div class="w-full md:max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm m-2 cursor-pointer" v-for="(service, index) in servicesList" :key="index">
                <figure>
                    <img class="rounded-t-lg max-h-32 w-full" :src="(service.image)" :alt="service.name" />
                </figure>
                <div class="p-5">
                    <div class="text-md font-bold tracking-tight text-gray-900">{{ service.name }}</div>
                    <small class="text-gray-600">{{service.neighborhood}} - {{service.city }}</small>
                </div>
                <div class="p-2">
                    <button class="mr-2 bg-yellow-400 p-2 rounded-md pl-3 pr-3 text-white font-semibold text-xs">Editar</button>
                    <button class="mr-2 bg-red-400 p-2 rounded-md pl-3 pr-3 text-white font-semibold text-xs">remover</button>
                </div>
            </div>
        </div>
    </section>
    <NewServiceModal :modal-settings="modalNewService" @putServiceInList="putService" />
</section>
</template>

<script>
import Utils from '@/assets/scripts/Utils';
import NavBarComponent from '@/components/Home/NavBarComponent.vue';
import BreadCrumbComponent from '@/components/painel/BreadCrumbComponent.vue';
import NewServiceModal from '@/components/painel/modals/NewServiceModal.vue';
import api from '@/services/api';

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

        putService: function (service) {
            this.servicesList = this.servicesList.concat(service);
        },

        toggleModalRegisterService: function () {
            this.modalNewService.show = !this.modalNewService.show;
        },

        getImage: function (path) {
            return api.getUri()
        },

        loadMyServices: function () {
            api.get('/enterprise/services/list', {
                headers: {
                    Authorization: `Bearer ${Utils.getEnterpriseSessionToken()}`
                }
            }).then((response) => {
                const services = response.data.data;
                this.servicesList = this.servicesList.concat(services);
            }).catch((error) => {

            });
        }
    },

    mounted() {
        this.loadMyServices();
    }
}
</script>
