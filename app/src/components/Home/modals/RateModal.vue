<template>
<div :class="config.show ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'" class="fixed inset-0 z-[999] grid w-screen place-items-center bg-black bg-opacity-60 backdrop-blur-sm transition-opacity duration-300 overflow-y-auto">
    <div class="relative m-4 p-4 w-2/5 min-w-[90%] max-w-[90%] md:min-w-[40%] md:max-w-[40%] rounded-lg bg-white shadow-sm">
        <div class="flex shrink-0 items-center pb-4 text-md font-semibold text-slate-800">
            {{ config.isEdit ? 'Editar Avaliação de' : 'Avaliar' }} "{{ config.serviceDetails.name }}"
        </div>

        <section v-if="isRequesting">
            <LoaderComponent />
        </section>

        <section v-if="!isRequesting">
            <AlertBoxComponent :alert="alert" class="mb-4" />
            <section v-if="isUserLogged()">
                <div class="flex space-x-1 mb-5 justify-center">
                    <div v-for="i in 5">
                        <svg :key="i" @click="setRating(i)" @mouseover="hoverRating = i" @mouseleave="hoverRating = 0" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 cursor-pointer transition-all duration-150" :class="[
            (hoverRating || rating) >= i ? 'text-yellow-400' : 'text-gray-300',
            'hover:scale-110'
          ]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 .587l3.668 7.431 8.2 1.191-5.934 5.782 1.4 8.168L12 18.896l-7.334 3.863 1.4-8.168L.132 9.209l8.2-1.191z" />
                        </svg>
                    </div>
                </div>

                <textarea v-model="comment" placeholder="Deixe seu comentário aqui..." class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" rows="4"></textarea>
            </section>

            <div class="flex shrink-0 flex-wrap items-center pt-4 justify-end">
                <button @click="hiddenModal()" class="rounded-md border border-transparent py-2 px-4 text-center text-sm transition-all text-slate-600 hover:bg-slate-100 focus:bg-slate-100 active:bg-slate-100 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" type="button">
                    Cancelar
                </button>
                <button v-if="hasUserLogged" @click="submitReview()" class="rounded-md bg-green-600 py-2 px-4 border border-transparent text-center text-sm text-white transition-all shadow-md hover:shadow-lg focus:bg-green-700 focus:shadow-none active:bg-green-700 hover:bg-green-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
                    {{ config.isEdit ? 'Editar' : 'Enviar' }}
                </button>
            </div>
        </section>
    </div>
</div>
</template>

<script>
import localBase from '@/assets/scripts/LocalBase';
import AlertBoxComponent from '../AlertBoxComponent.vue';
import api from '@/services/api';
import LoaderComponent from '../LoaderComponent.vue';
import Utils from '@/assets/scripts/Utils';

export default {
    name: 'RateModal',
    props: ['config'],
    emits: ['changeReview'],
    components: {
        AlertBoxComponent,
        LoaderComponent
    },

    data() {
        return {
            userLoggedInfo: {},
            hasUserLogged: false,
            isRequesting: false,

            alert: {
                show: false,
                success: false,
                message: ''
            },

            rating: (this.config.isEdit ? this.config.serviceDetails.stars : 0),
            hoverRating: (this.config.isEdit ? this.config.serviceDetails.stars : 0),
            comment: (this.config.isEdit ? this.config.serviceDetails.comment : ''),
            submitted: false,

            postedReviews: [],
        }
    },

    watch: {
        config: {
            handler(newVal) {
                if (newVal.show && newVal.isEdit) {
                    this.rating = newVal.serviceDetails.stars || 0;
                    this.hoverRating = newVal.serviceDetails.stars || 0;
                    this.comment = newVal.serviceDetails.comment || '';
                } else if (newVal.show && !newVal.isEdit) {
                    this.resetRating();
                }
            },
            deep: true,
            immediate: true
        }
    },

    methods: {
        setRating: function (value) {
            this.rating = value;
        },

        resetRating: function () {
            this.alert.show = false;
            this.alert.message = '';
            this.rating = 0;
            this.comment = '';
        },

        submitReview: function () {
            if (this.rating === 0 || this.comment.trim() === "") {
                this.alert.show = true;
                this.alert.message = 'É necessário dar uma nota e escrever um comentário!';
                return;
            }

            this.isRequesting = true;
            const token = Utils.getUserSessionToken();

            let url = '/review/put';
            if (this.config.isEdit !== undefined && this.config.isEdit == true) {
                url = `/review/update/${this.config.serviceDetails.reviewId}`;
            }

            api.post(url, {
                isEdit: (this.config.isEdit !== undefined && this.config.isEdit ? true : false),
                enterprise_service_id: this.config.serviceDetails.enterprise_id,
                stars: this.rating,
                comment: (this.comment ? this.comment : ''),
            }, {
                headers: {
                    'Content-Type': 'application/json',
                    Authorization: `Bearer ${token}`
                }
            }).then((response) => {
                this.isRequesting = false;

                if ([200, 201].includes(Number(response.status))) {
                    const rate = response.data.data;
                    this.postedReviews.push(rate);

                    this.$emit('changeReview', rate);

                    this.alert.show = true;
                    this.alert.success = true;
                    this.alert.message = response.data.message;
                    return;
                }

                this.alert.show = true;
                this.alert.success = false;
                this.alert.message = 'Não foi possível realizar sua avaliação. Por favor, aguarde ou contate nosso suporte!';
            }).catch((error) => {
                this.isRequesting = false;
                this.alert.show = true;
                this.alert.success = false;
                console.log(error);

                if (error.response && [400, 401, 422].includes(error.status)) {
                    this.alert.message = error.response.data.message;
                } else {
                    this.alert.message = 'Serviço indisponível no momento. Por favor, aguarde ou contate nosso suporte!';
                }
            })
        },

        isUserLogged: function () {
            try {
                const userData = localBase.select(localBase.keys.login.user);
                if (userData) {
                    this.userLoggedInfo = JSON.parse(userData);
                    this.hasUserLogged = true;
                    return true;
                }

                this.alert.show = true;
                this.alert.message = 'É necessário realizar o login para postar avaliações!';
                return false;

            } catch (e) {
                this.alert.show = true;
                this.alert.message = 'É necessário realizar o login para postar avaliações!';
                return false;
            }
        },

        hiddenModal: function () {
            this.resetRating();
            this.config.show = false;
        },
    }
}
</script>
