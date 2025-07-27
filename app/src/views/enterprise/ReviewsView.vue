<template>
<section class="min-h-screen flex flex-col bg-gray-50">
    <NavBarComponent />

    <section class="w-full max-w-7xl mx-auto p-5">
        <BreadCrumbComponent :breadcrumb="loadBreadCrumb()" />

        <section v-if="isRequesting">
            <LoaderComponent />
        </section>

        <section v-if="!isRequesting && !Object.keys(analytics).length">
            <AlertBoxComponent :alert="alert" />
        </section>

        <section>
            <div class="max-w-2xl mx-auto p-4">
                <h2 class="text-xl font-bold mb-4 text-green-700">Ranking de Serviços</h2>

                <ul class="space-y-4">
                    <li v-for="item in analytics" :key="item.id" class="p-4 rounded border shadow-sm">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-semibold text-gray-800">{{ item.name }}</h3>
                            <span class="text-sm text-gray-600">{{ item.total_ratings }} avaliações</span>
                        </div>

                        <div class="flex items-center space-x-1">
                            <div v-for="n in 5" :key="n">
                                <svg v-if="n <= Math.floor(item.average_stars)" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.462a1 1 0 00-.364 1.118l1.287 3.974c.3.921-.755 1.688-1.54 1.118L10 13.347l-3.388 2.462c-.784.57-1.838-.197-1.539-1.118l1.287-3.974a1 1 0 00-.364-1.118L3.608 9.4c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.974z" />
                                </svg>
                                <svg v-else-if="n === Math.ceil(item.average_stars) && item.average_stars % 1 !== 0" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-400" fill="url(#halfGrad)" viewBox="0 0 20 20">
                                    <defs>
                                        <linearGradient id="halfGrad">
                                            <stop offset="50%" stop-color="currentColor" />
                                            <stop offset="50%" stop-color="transparent" />
                                        </linearGradient>
                                    </defs>
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.462a1 1 0 00-.364 1.118l1.287 3.974c.3.921-.755 1.688-1.54 1.118L10 13.347l-3.388 2.462c-.784.57-1.838-.197-1.539-1.118l1.287-3.974a1 1 0 00-.364-1.118L3.608 9.4c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.974z" />
                                </svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.462a1 1 0 00-.364 1.118l1.287 3.974c.3.921-.755 1.688-1.54 1.118L10 13.347l-3.388 2.462c-.784.57-1.838-.197-1.539-1.118l1.287-3.974a1 1 0 00-.364-1.118L3.608 9.4c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.974z" />
                                </svg>
                            </div>
                            <span class="ml-2 text-sm text-gray-700 font-medium">
                                {{ Number(item.average_stars).toFixed(1) }}
                            </span>
                        </div>
                    </li>
                </ul>
            </div>
        </section>

    </section>
</section>
</template>

<script>
import Utils from '@/assets/scripts/Utils';
import AlertBoxComponent from '@/components/Home/AlertBoxComponent.vue';
import LoaderComponent from '@/components/Home/LoaderComponent.vue';
import NavBarComponent from '@/components/Home/NavBarComponent.vue';
import BreadCrumbComponent from '@/components/painel/BreadCrumbComponent.vue';
import api from '@/services/api';

export default {
    name: 'EnterpriseReviewsView',
    components: {
        NavBarComponent,
        BreadCrumbComponent,
        LoaderComponent,
        AlertBoxComponent,
    },

    data() {
        return {
            isRequesting: true,
            analytics: {},
            alert: {
                show: false,
                success: false,
                message: ''
            },
        }
    },

    methods: {
        loadBreadCrumb: function () {
            return [{
                name: 'Avaliações',
                path: '/empresa/avaliacoes'
            }];
        },

        fetchAnalytics: function () {
            this.isRequesting = true;
            api.get('/enterprise/services/analytics', {
                headers: {
                    Authorization: `Bearer ${Utils.getEnterpriseSessionToken()}`
                }
            }).then((response) => {
                this.isRequesting = false;
                if (response.status == 200) {
                    this.analytics = response.data.data;
                    return;
                }

                this.alert.show = true;
                this.alert.message = 'Não foi possível encontrar dados para fazer o levantamento de informações';

            }).catch((error) => {
                this.isRequesting = false;
                if (error.status == 401) {
                    return Utils.destroySessionWithLoginRequired();
                }

                this.alert.show = true;
                this.alert.message = 'Ocorreu um erro inesperado. Por favor, tente novamente mais tarde!';
            })
        }
    },

    created() {
        this.fetchAnalytics();
    }
}
</script>
