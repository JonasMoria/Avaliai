<template>
<section class="min-h-screen flex flex-col bg-gray-50">
    <NavBarComponent />

    <section class="w-full max-w-7xl mx-auto p-5">
        <BreadCrumbComponent :breadcrumb="loadBreadCrumb()" />
        <button @click="toggleModalRegisterService()" type="button" class="bg-green-400 p-2 rounded-md pl-4 pr-4 text-sm text-white font-bold hover:shadow-lg">Cadastrar</button>

        <section v-show="isRequesting">
            <LoaderComponent />
        </section>

        <section class="mt-5">
            <AlertBoxComponent :alert="alert" />
        </section>

        <section v-show="!isRequesting">
            <div class="flex flex-wrap w-full mt-5">
                <div class="w-full md:max-w-xs bg-white border border-gray-200 rounded-lg shadow-sm m-2 cursor-pointer" v-for="(service, index) in servicesList" :key="index">
                    <figure>
                        <img class="rounded-t-lg max-h-32 w-full" :src="(service.image)" :alt="service.name" />
                    </figure>
                    <div class="p-5">
                        <div class="text-md font-bold tracking-tight text-gray-900">{{ service.name }}</div>
                        <small class="text-gray-600">{{service.neighborhood}} - {{service.city }}</small>
                    </div>
                    <div class="p-2">
                        <button @click="editService(index)" class="mr-2 bg-yellow-400 p-2 rounded-md pl-3 pr-3 text-white font-semibold text-xs">Editar</button>
                        <button @click="removeService(index)" class="mr-2 bg-red-400 p-2 rounded-md pl-3 pr-3 text-white font-semibold text-xs">remover</button>
                    </div>
                </div>
            </div>
        </section>

    </section>
    <NewServiceModal :modal-settings="modalNewService" @putServiceInList="putService" @updateServiceInList="updateServiceList" />
    <ConfirmDeleteModal :modal-confirm-settings="modalConfirmSettings" :confirmDelete="removeService" />
</section>
</template>

<script>
import Utils from '@/assets/scripts/Utils';
import AlertBoxComponent from '@/components/Home/AlertBoxComponent.vue';
import LoaderComponent from '@/components/Home/LoaderComponent.vue';
import NavBarComponent from '@/components/Home/NavBarComponent.vue';
import BreadCrumbComponent from '@/components/painel/BreadCrumbComponent.vue';
import ConfirmDeleteModal from '@/components/painel/modals/ConfirmDeleteModal.vue';
import NewServiceModal from '@/components/painel/modals/NewServiceModal.vue';
import api from '@/services/api';

export default {
    name: 'ServicesView',

    components: {
        NavBarComponent,
        BreadCrumbComponent,
        NewServiceModal,
        ConfirmDeleteModal,
        LoaderComponent,
        AlertBoxComponent
    },

    data() {
        return {
            isRequesting: false,

            modalNewService: {
                show: false,
                isEdit: false,
                objEdit: {},
            },
            modalConfirmSettings: {
                show: false,
                alertMessage: '',
                id: 0,
            },
            alert: {
                show: false,
                success: false,
                message: ''
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
                if (error.status == 401) {
                    return Utils.destroySessionWithLoginRequired();
                }
            });
        },

        editService: function (index) {
            const item = this.servicesList[index];

            this.modalNewService.isEdit = true;
            this.modalNewService.objEdit = item;
            this.modalNewService.show = true;
        },

        updateServiceList: function (object) {
            const index = this.servicesList.findIndex(item => item.id === object.id);
            if (index !== -1) {
                this.servicesList[index] = object;
            }
        },

        removeService: function (index, isConfirmed = false) {
            const item = this.servicesList[index];

            if (!isConfirmed) {
                this.modalConfirmSettings.alertMessage = `Tem certeza que deseja remover ${item.name} da sua lista?`;
                this.modalConfirmSettings.id = index;
                this.modalConfirmSettings.show = true;
                return;
            }

            this.modalConfirmSettings.show = false;
            this.isRequesting = true;
            api.delete(`/enterprise/services/delete/${item.id}`, {
                headers: {
                    Authorization: `Bearer ${Utils.getEnterpriseSessionToken()}`,
                }
            }).then((response) => {
                this.isRequesting = false;
                this.alert.success = true;
                this.alert.message = response.data.message;
                this.alert.show = true;
                this.servicesList.splice(index, 1);

            }).catch((error) => {
                this.isRequesting = false;
                this.alert.success = false;
                this.alert.message = (error.data.message ? error.data.message : 'Não foi possível remover esse item. Por favor, tente novamente mais tarde!');
                this.alert.show = true;
            });
        }
    },

    mounted() {
        this.loadMyServices();
    }
}
</script>
