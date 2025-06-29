<template>
<section class="min-h-screen flex flex-col">
    <NavBarComponent />

    <section class="flex-grow bg-gray-50 border-b-2">
        <section v-if="isRequesting">
            <LoaderComponent />
        </section>

        <section v-if="!isRequesting && !enterprise" class="flex justify-center items-center min-h-screen">
            <NotFoundAlertComponent />
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

export default {
    name: 'ServicePage',

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
        fetchService: function (serviceId) {
            api.get(`/search/service/${serviceId}`)
                .then((response) => {
                    this.isRequesting = false;
                    if (response.status == 200) {
                        this.enterprise = response.data.data;
                        return;
                    }
                })
                .catch((error) => {
                    this.isRequesting = false;
                });
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
