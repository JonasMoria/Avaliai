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

                <div v-if="!serviceRates.length" class="flex items-center p-3 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50" role="alert">
                    <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <div class="text-xs">
                        <span class="font-medium">Ops!</span> Nada por aqui ainda... Estamos esperando por você para começar essa história. Avalie e construa reputações!
                    </div>
                </div>
                <div v-if="serviceRates.length" v-for="(rate, index) in serviceRates" :key="rate.id" class="w-full mx-auto bg-white shadow-md rounded-2xl p-5 mb-4 flex flex-col gap-4 border border-gray-200">
                    <div class="flex items-center gap-4">
                        <img :src="rate.user.profile_photo ? rate.user.profile_photo : getNoPhoto()" :alt="`Foto de ${rate.user.name} ${rate.user.surname}`" class="w-12 h-12 rounded-full border-2 border-green-500 object-cover" />
                        <div>
                            <h3 class="text-md font-semibold text-gray-800">{{ rate.user.name }} {{ rate.user.surname }}</h3>
                            <p class="text-xs text-gray-500">{{ rate.updated_at }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-1 text-yellow-400">
                        <template v-for="n in 5">
                            <svg v-if="n <= rate.stars" class="w-5 h-5 fill-current" viewBox="0 0 20 20" :key="`filled-${n}`">
                                <path d="M10 15l-5.878 3.09L5.5 12.18.999 7.91l6.08-.885L10 1l2.921 6.025 6.08.885-4.5 4.27 1.378 5.91z" />
                            </svg>
                            <svg v-else class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" :key="`empty-${n}`">
                                <path d="M10 15l-5.878 3.09L5.5 12.18.999 7.91l6.08-.885L10 1l2.921 6.025 6.08.885-4.5 4.27 1.378 5.91z" />
                            </svg>
                        </template>
                        <span class="ml-2 text-sm font-medium text-gray-700">({{ rate.stars }} estrela{{ rate.stars > 1 ? 's' : '' }})</span>
                    </div>

                    <p class="text-gray-800 text-base leading-relaxed italic">“{{ rate.comment }}”</p>
                </div>

            </div>
        </section>
    </section>

    <FooterComponent />
    <RateModal :config="modalRate" />
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
            serviceRates: [],

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

        fetchRates: function (serviceId) {
            api.get(`/search/service/${serviceId}/rates`)
                .then((response) => {
                    this.isRequesting = false;
                    if (response.status == 200) {
                        this.serviceRates = response.data.data;
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
        openModalRating: function () {
            this.modalRate.serviceDetails = this.service;
            this.modalRate.show = true;
        },
        getNoPhoto: function () {
            return new URL('@/assets/icons/nophoto.svg',
                import.meta.url).href;
        }
    },

    watch: {
        '$route.params.id'(id) {
            this.fetchService(id);
            this.fetchRates(id);
        }
    },

    created() {
        const serviceId = this.$route.params.id;
        if (!serviceId) {
            this.isRequesting = false;
            return;
        }

        this.fetchService(serviceId);
        this.fetchRates(serviceId);
    }
}
</script>
