<template>
<section class="min-h-screen flex flex-col bg-gray-50">
    <NavBarComponent />

    <section class="w-full max-w-7xl mx-auto p-5">
        <BreadCrumbComponent :breadcrumb="loadBreadCrumb()" />
    </section>

    <section v-if="isRequesting">
        <LoaderComponent />
    </section>

    <section class="w-full mx-auto max-w-7xl mb-10">
        <div v-if="!isRequesting && !reviews.length" class="flex items-center justify-center p-3 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 mx-5" role="alert">
            <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <div class="text-xs">
                <span class="font-medium">Ops!</span> Nada por aqui ainda... Estamos esperando por você! Avalie e construa reputações!
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6 p-4">
            <div v-for="(rating, index) in reviews" :key="index" class="bg-white cursor-pointer rounded-2xl shadow-md border border-gray-200 overflow-hidden flex flex-col transition-transform duration-300 transform hover:scale-105">
                <img :src="rating.service.image" :alt="`Imagem de ${rating.service.name}`" class="w-full h-24 md:h-36 object-cover" />

                <div class="p-4 flex flex-col gap-2">
                    <h2 class="text-sm md:text-md font-bold text-gray-800">
                        {{ rating.service.name }}
                    </h2>

                    <div class="flex items-center gap-1 text-yellow-400">
                        <template v-for="n in 5">
                            <svg v-if="n <= rating.stars" class="w-5 h-5 fill-current" viewBox="0 0 20 20" :key="`filled-${n}`">
                                <path d="M10 15l-5.878 3.09L5.5 12.18.999 7.91l6.08-.885L10 1l2.921 6.025 6.08.885-4.5 4.27 1.378 5.91z" />
                            </svg>
                            <svg v-else class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" :key="`empty-${n}`">
                                <path d="M10 15l-5.878 3.09L5.5 12.18.999 7.91l6.08-.885L10 1l2.921 6.025 6.08.885-4.5 4.27 1.378 5.91z" />
                            </svg>
                        </template>
                    </div>

                    <p class="text-gray-700 italic text-sm md:text-md">"{{ rating.comment }}"</p>
                    <p class="text-xs text-gray-500">🕒 {{ rating.updated_at }}</p>
                    <div class="flex gap-2 mt-3">
                        <button @click="openModalEditReview(rating)" class="flex-1 bg-yellow-100 text-yellow-700 hover:bg-yellow-200 px-3 py-1 rounded-md text-sm font-medium">
                            Editar
                        </button>
                        <button @click="removeReview(rating.id)" class="flex-1 bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1 rounded-md text-sm font-medium">
                            Excluir
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </section>

</section>
</template>

<script>
import Utils from '@/assets/scripts/Utils';
import LoaderComponent from '@/components/Home/LoaderComponent.vue';
import NavBarComponent from '@/components/Home/NavBarComponent.vue';
import NotFoundAlertComponent from '@/components/Home/NotFoundAlertComponent.vue';
import BreadCrumbComponent from '@/components/painel/BreadCrumbComponent.vue';
import api from '@/services/api';

export default {
    name: 'UserReviewsView',
    components: {
        NavBarComponent,
        BreadCrumbComponent,
        LoaderComponent,
        NotFoundAlertComponent
    },

    data() {
        return {
            isRequesting: true,
            reviews: [],
        }
    },

    methods: {
        loadBreadCrumb: function () {
            return [{
                name: 'Avaliações',
                path: '/usuario/avaliacoes'
            }];
        },

        fetchReviews: function () {
            api.get('/user/reviews/get', {
                headers: {
                    Authorization: `Bearer ${Utils.getUserSessionToken()}`
                }
            }).then((response) => {
                this.isRequesting = false;
                const data = response.data.data;

                if (response.status == 200) {
                    this.reviews = data;
                    return;
                }
            }).catch((error) => {
                this.isRequesting = false;
            });
        },
    },

    created() {
        this.fetchReviews();
    }
}
</script>
