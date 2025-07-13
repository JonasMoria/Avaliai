<template>
<section class="min-h-screen flex flex-col bg-gray-50">
    <NavBarComponent />

    <section class="flex-grow bg-gray-50 max-w-7xl mx-auto">
        <section v-if="isRequesting">
            <LoaderComponent />
        </section>

        <section v-if="!isRequesting && !Object.keys(enterprise).length" class="flex justify-center items-center min-h-screen">
            <NotFoundAlertComponent />
        </section>

        <section v-if="!isRequesting && Object.keys(enterprise).length">
            <div class="mt-10 flex items-center space-x-4">
                <img class="w-14 h-14 md:w-24 md:h-24 p-1 rounded-full ring-2 ring-green-300" :src="enterprise.profile_photo" :alt="`logo_${enterprise.tradename}`">

                <div class="flex flex-col">
                    <h4 class="text-md md:text-lg text-green-800 leading-tight font-bold">
                        {{ enterprise.name }}
                    </h4>
                    <small class="font-semibold text-xs md:text-sm text-green-600">
                        {{ enterprise.tradename }} | {{ enterprise.cnpj }}
                    </small>
                    <small class="font-semibold text-xs md:text-sm text-green-600">
                        {{ enterprise.email }}
                    </small>
                </div>
            </div>

            <div v-if="!services.length" class="flex items-center p-3 mt-10 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50" role="alert">
                <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                </svg>
                <div class="text-xs">
                    <span class="font-medium">Ops!</span> Nada por aqui ainda... Em breve essa empresa deve cadastrar seus serviços ou produtos!
                </div>
            </div>

            <div v-if="services.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
                <div @click="goToService(service.id, service.name)" v-for="(service, index) in services" :key="index" class="bg-white cursor-pointer rounded-2xl shadow-lg border border-gray-200 overflow-hidden flex flex-col transition-transform duration-300 transform hover:scale-105">
                    <img :src="service.image" :alt="`Imagem de ${service.name}`" class="w-full h-36 object-cover" />

                    <div class="p-4 flex flex-col gap-2">
                        <h2 class="text-md font-bold text-gray-800">{{ service.name }}</h2>

                        <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full w-max uppercase">
                            {{ service.type }}
                        </span>

                        <p class="text-xs text-gray-600 leading-snug">
                            📍 {{ service.street }}, {{ service.number }}<br>
                            {{ service.neighborhood }} - {{ service.city }}/{{ service.state }}<br>
                            CEP: {{ formatPostalCode(service.postalCode) }}
                        </p>

                        <p class="text-xs text-gray-600">
                            📞 {{ formatPhone(service.phone) }}<br>
                            ✉️ {{ service.email }}
                        </p>

                        <a :href="service.website" target="_blank" class="mt-2 text-xs font-medium text-blue-600 hover:underline">
                            🌐 Acessar site
                        </a>
                    </div>
                </div>
            </div>

        </section>
    </section>

    <FooterComponent />
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

export default {
    name: 'EnterprisePage',

    components: {
        NavBarComponent,
        FooterComponent,
        LoaderComponent,
        NotFoundAlertComponent,
    },

    data() {
        return {
            isRequesting: true,
            enterprise: {},
            services: [],
        }
    },

    methods: {
        fetchEnterprise: function (enterpriseId) {
            api.get(`/search/enterprise/${enterpriseId}`)
                .then((response) => {
                    this.isRequesting = false;
                    if (response.status == 200) {
                        this.enterprise = response.data.data;
                        this.enterprise.cnpj = Utils.maskCnpj(this.enterprise.cnpj);
                        return;
                    }
                })
                .catch((error) => {
                    this.isRequesting = false;
                });
        },

        fetchServices: function (enterpriseId) {
            api.get(`/enterprise/${enterpriseId}/services/list`)
                .then((response) => {
                    this.isRequesting = false;
                    if (response.status == 200) {
                        this.services = response.data.data;
                        return;
                    }
                })
                .catch((error) => {
                    this.isRequesting = false;
                });
        },

        goToService: function (id, name) {
            router.push({
                path: `/empresa/item/${Utils.filterWord(name)}/${id}`
            });
        },

        formatPhone: function (phone) {
            return Utils.maskPhone(phone);
        },
        formatPostalCode: function (cep) {
            return Utils.maskCep(cep);
        }
    },

    watch: {
        '$route.params.id'(id) {
            this.fetchEnterprise(id);
            this.fetchServices(id);
        }
    },

    created() {
        const enterpriseId = this.$route.params.id;
        if (!enterpriseId) {
            this.isRequesting = false;
            return;
        }

        this.fetchEnterprise(enterpriseId);
        this.fetchServices(enterpriseId);
    }
}
</script>
