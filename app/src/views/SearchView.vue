<template>
<section class="min-h-screen flex flex-col">
    <NavBarComponent />

    <section class="flex-grow bg-gray-50 border-b-2">
        <section v-if="isRequesting">
            <LoaderComponent />
        </section>

        <section v-if="!isRequesting && (!results || results.length === 0)" class="flex justify-center items-center min-h-screen">
            <div class="flex flex-col items-center gap-6">
                <img src="@/assets/images/not_found.png" alt="content_not_found" class="max-w-xs">
                <button @click="backHome()" class="text-green-600 font-semibold border border-green-400 text-lg px-8 py-3 rounded-md shadow hover:bg-green-500 hover:text-white">
                    Voltar
                </button>
            </div>
        </section>

        <section v-if="!isRequesting && (results && results.length)" class="flex w-full max-w-7xl mx-auto">
            <div class="flex flex-wrap w-full mt-5">
                <div @click="goToItem(item)" class="w-full max-w-40 md:max-w-56 bg-white border border-gray-200 rounded-lg shadow-sm m-2 cursor-pointer" v-for="(item, index) in results" :key="index">
                    <figure class="justify-center items-center">
                        <img class="rounded-t-lg w-full" :src="(item.image)" :alt="item.name" />
                    </figure>
                    <div class="p-5">
                        <div class="text-sm font-bold tracking-tight text-gray-900">{{ item.name }}</div>
                    </div>
                </div>
            </div>
        </section>

        <div v-if="!isRequesting && (results && results.length > ((page - 1) * 30))" class="flex justify-center mt-8 mb-8">
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

export default {
    name: 'SearchView',

    components: {
        NavBarComponent,
        FooterComponent,
        LoaderComponent,
    },

    data() {
        return {
            isRequesting: true,
            page: 1,
            results: [],
        }
    },

    methods: {
        backHome: function () {
            router.push({
                path: `/`
            });
        },

        goToItem: function (item) {
            if (item.type === 1) {
                router.push({
                    path: `/empresa/${Utils.filterWord(item.name)}/${item.id}`
                });
            } else if (item.type === 2) {
                router.push({
                    path: `/empresa/item/${Utils.filterWord(item.name)}/${item.id}`
                });
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
