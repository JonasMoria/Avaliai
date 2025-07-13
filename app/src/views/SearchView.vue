<template>
<section class="min-h-screen flex flex-col">
    <NavBarComponent />

    <section class="flex-grow bg-gray-50 border-b-2">
        <section v-if="isRequesting">
            <LoaderComponent />
        </section>

        <section v-if="!isRequesting && (!results || results.length === 0)" class="flex justify-center items-center min-h-screen">
            <NotFoundAlertComponent />
        </section>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
            <div @click="goToItem(item)" v-for="(item, index) in results" :key="item.id" class="bg-white cursor-pointer rounded-2xl shadow-lg border border-gray-200 overflow-hidden flex flex-col
           transition-transform duration-300 transform hover:scale-105">
                <img :src="item.image" :alt="`Imagem de ${item.name}`" class="w-full h-48 object-cover" />

                <div class="p-4 flex flex-col gap-2">
                    <h2 class="text-lg font-bold text-gray-800">{{ item.name }}</h2>

                    <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full w-max uppercase">
                        {{ getTypeLabel(item.type) }}
                    </span>
                </div>
            </div>
        </div>

        <div v-if="!isRequesting && results && results.length === page * 30" class="flex justify-center mt-8 mb-8">
            <button @click="loadMore()" class="bg-green-600 text-white px-6 py-2 rounded-md shadow-md hover:bg-green-700">
                Ver mais
            </button>
        </div>
    </section>

    <FooterComponent />
</section>
</template>

<script>
import NavBarComponent from '@/components/Home/NavBarComponent.vue';
import FooterComponent from '@/components/Home/FooterComponent.vue';
import LoaderComponent from '@/components/Home/LoaderComponent.vue';
import api from '@/services/api';
import router from '@/router';
import Utils from '@/assets/scripts/Utils';
import NotFoundAlertComponent from '@/components/Home/NotFoundAlertComponent.vue';

export default {
    name: 'SearchView',

    components: {
        NavBarComponent,
        FooterComponent,
        LoaderComponent,
        NotFoundAlertComponent
    },

    data() {
        return {
            isRequesting: true,
            page: 1,
            results: [],
        }
    },

    methods: {
        goToItem: function (item) {
            if (item.type === 1) {
                router.push({
                    path: `/empresa/item/${Utils.filterWord(item.name)}/${item.id}`
                });
            } else if (item.type === 2) {
                router.push({
                    path: `/empresa/${Utils.filterWord(item.name)}/${item.id}`
                });
            }
        },

        getTypeLabel(type) {
            switch (type) {
                case 1:
                    return "Local/Serviço/Produto";
                case 2:
                    return "Empresa/Órgão Público";
                default:
                    return "Outro";
            }
        },

        loadMore: function () {
            const term = this.$route.params.term;
            this.page++;
            this.fetchResults(term, true);
        },

        fetchResults: function (term, push = false) {
            api.get(`/search/all?term=${encodeURIComponent(term)}&page=${this.page}`)
                .then((response) => {
                    if (response.status == 200) {
                        const data = response.data.data;
                        this.isRequesting = false;
                        if (push) {
                            this.results.push(...data);
                        } else {
                            this.results = data;
                        }
                    }

                }).catch((error) => {
                    this.isRequesting = false;
                });
        }
    },

    watch: {
        '$route.params.term'(newTerm) {
            this.fetchResults(newTerm);
        }
    },

    created() {
        const term = this.$route.params.term;
        this.fetchResults(term);
    }
}
</script>
