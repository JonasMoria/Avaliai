<template>
<section class="min-h-screen flex flex-col">
    <NavBarComponent />

    <section class="flex-grow bg-gray-50 border-b-2">
        <section v-if="isRequesting">
            <LoaderComponent />
        </section>

        <section v-if="!isRequesting && !Object.keys(service).length" class="flex justify-center items-center min-h-screen">
            <NotFoundAlertComponent />
        </section>

        <section v-if="!isRequesting && Object.keys(service).length">
            <div class="max-w-4xl mx-auto p-6 bg-white rounded-2xl shadow-lg mt-10 mb-10">
                <div class="flex items-center gap-3 cursor-pointer" @click="goToEnterprise(service)" :title="`Visualizar perfil de ${service.enterprise.tradename}`">
                    <img class="w-14 h-14 md:w-18 md:h-18 rounded-full ring-2 ring-green-400" :src="service.enterprise.profile_photo" :alt="service.enterprise.tradename">
                    <div>
                        <h2 class="text-xl font-bold text-green-800">{{ service.enterprise.tradename }}</h2>
                    </div>
                </div>
                <div class="mt-6 overflow-hidden flex justify-center items-center">
                    <img :src="service.image" :alt="service.name" class="h-60 rounded-xl object-cover">
                </div>
                <div class="mt-6 space-y-2">
                    <h3 class="text-xl font-semibold text-green-700 uppercase">{{ service.name }}</h3>
                    <p class="text-sm text-gray-600"><span class="font-medium text-gray-800">Tipo:</span> {{ service.type }}</p>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium text-gray-800">Endereço:</span>
                        {{ service.street }}, {{ service.number }} - {{ service.neighborhood }}, {{service.city}}/{{ service.state }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium text-gray-800">CEP:</span> {{ service.postalCode }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium text-gray-800">Telefone:</span> {{ service.phone }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium text-gray-800">Email:</span> {{ service.email }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium text-gray-800">Website:</span>
                        <a :href="service.website" class="text-green-600 hover:underline" target="_blank">
                            Acessar site
                        </a>
                    </p>
                </div>
                <div class="w-full mt-5 mb-5 flex justify-center items-center">
                    <button @click="openModalRating()" class="border-2 border-green-400 rounded-md text-green-600 px-7 py-2 hover:bg-green-300 hover:text-green-900">Avaliar!</button>
                </div>
            </div>
        </section>
    </section>

    <FooterComponent />
    <RateModal :config="modalRate"/>
</section>
</template>

<script>
import NavBarComponent from '@/components/Home/NavBarComponent.vue';
import FooterComponent from '@/components/Home/FooterComponent.vue';
import LoaderComponent from '@/components/Home/LoaderComponent.vue';
import NotFoundAlertComponent from '@/components/Home/NotFoundAlertComponent.vue';
import api from '@/services/api';
import Utils from '@/assets/scripts/Utils';
import router from '@/router';
import RateModal from '@/components/Home/modals/RateModal.vue';

export default {
    name: 'ServicePage',

    components: {
        NavBarComponent,
        FooterComponent,
        LoaderComponent,
        NotFoundAlertComponent,
        RateModal,
    },

    data() {
        return {
            isRequesting: true,
            service: {},

            modalRate: {
                show: false,
                serviceDetails: {},
            }
        }
    },

    methods: {
        fetchService: function (serviceId) {
            api.get(`/search/service/${serviceId}`)
                .then((response) => {
                    this.isRequesting = false;
                    if (response.status == 200) {
                        this.service = response.data.data;
                        this.service.postalCode = Utils.maskCep(this.service.postalCode);
                        this.service.phone = Utils.maskPhone(this.service.phone);
                        return;
                    }
                })
                .catch((error) => {
                    this.isRequesting = false;
                });
        },
        goToEnterprise: function (service) {
            router.push({
                path: `/empresa/${Utils.filterWord(service.enterprise.tradename)}/${service.enterprise.id}`
            });
        },

        openModalRating: function() {
            this.modalRate.serviceDetails = this.service;
            this.modalRate.show = true;
        }
    },

    watch: {
        '$route.params.id'(id) {
            this.fetchService(id);
        }
    },

    created() {
        const serviceId = this.$route.params.id;
        if (!serviceId) {
            this.isRequesting = false;
            return;
        }

        this.fetchService(serviceId);
    }
}
</script>
