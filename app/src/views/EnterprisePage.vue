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
        }
    },

    watch: {
        '$route.params.id'(id) {
            this.fetchEnterprise(id);
        }
    },

    created() {
        const enterpriseId = this.$route.params.id;
        if (!enterpriseId) {
            this.isRequesting = false;
            return;
        }

        this.fetchEnterprise(enterpriseId);
    }
}
</script>
