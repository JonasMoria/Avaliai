<template>
<section class="min-h-screen flex flex-col bg-gray-50">
    <NavBarComponent />

    <section class="w-full max-w-7xl mx-auto p-5">
        <BreadCrumbComponent :breadcrumb="loadBreadCrumb()" />

        <section v-if="isRequesting">
            <LoaderComponent />
        </section>

        <AlertBoxComponent :alert="alert" />
        <section class="md:flex md:grid-cols-2 md:gap-4" v-if="!isRequesting">
            <section class="w-full m-1 border border-gray-200 rounded-md p-5 flex flex-col">
                <div class="flex flex-row mb-5">
                    <img src="@/assets/icons/media.svg" alt="company_icon" class="h-6">
                    <h3 class="ml-2 text-md font-semibold text-green-600 mt-[0.2%]">Imagem de Perfil</h3>
                </div>
                <figure class="mt-auto flex justify-center items-cente mb-4">
                    <img :src="payload.media.perfilImage" class="w-32 h-32 rounded-full ring-2 ring-gray-300" alt="Perfil Logo">
                </figure>
                <div class="mt-auto text-xs">
                    <input @change="handleImageUpload" class="block w-full p-1 mb-1 text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" id="file_input" type="file">
                    <button @click="updateImage()" class="bg-green-500 p-2 px-4 text-xs rounded-md text-white font-semibold w-full">Atualizar</button>
                </div>
            </section>

            <section class="w-full m-1 border border-gray-200 rounded-md p-5 flex flex-col">
                <div class="flex flex-row mb-2">
                    <img src="@/assets/icons/company.svg" alt="company_icon" class="h-6">
                    <h3 class="ml-2 text-md font-semibold text-green-600 mt-[0.2%]">Minha empresa</h3>
                </div>
                <div class="mt-auto">
                    <div class="m-2">
                        <label for="enterprise_name" class="block mb-2 text-xs font-medium text-gray-500">Razão Social</label>
                        <input v-model="payload.infos.name" type="text" id="enterprise_name" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-500 text-xs rounded-lg block w-full p-2" />
                    </div>
                    <div class="m-2">
                        <label for="tradename" class="block mb-2 text-xs font-medium text-gray-500">Nome Fantasia</label>
                        <input v-model="payload.infos.tradename" type="text" id="tradename" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-500 text-xs rounded-lg block w-full p-2" />
                    </div>
                    <div class="m-2">
                        <label for="enterprise_cnpj" class="block mb-2 text-xs font-medium text-gray-500">CNPJ</label>
                        <input v-model="payload.infos.cnpj" type="text" id="enterprise_cnpj" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-500 text-xs rounded-lg block w-full p-2" />
                    </div>
                </div>
                <div class="mt-auto">
                    <button @click="updateMyEnterprise()" class="bg-green-500 p-2 px-4 text-xs rounded-md text-white font-semibold w-full">Atualizar</button>
                </div>
            </section>

            <section class="w-full m-1 border border-gray-200 rounded-md p-5">
                <div class="flex flex-row mb-2">
                    <img src="@/assets/icons/security.svg" alt="company_icon" class="h-6">
                    <h3 class="ml-2 text-md font-semibold text-green-600 mt-[0.2%]">Login</h3>
                </div>
                <div class="mt-auto">
                    <div class="m-2">
                        <label for="enterprise_email" class="block mb-2 text-xs font-medium text-gray-500">E-mail</label>
                        <input v-model="payload.security.email" type="text" id="enterprise_email" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-500 text-xs rounded-lg block w-full p-2" />
                        <div class="mt-2">
                            <button @click="updateEmail()" class="bg-green-500 p-2 px-4 text-xs rounded-md text-white font-semibold w-full">Atualizar</button>
                        </div>
                    </div>
                    <!-- Campo de Senha -->
                    <div class="m-2 relative">
                        <label for="password" class="block mb-3 text-xs font-medium text-gray-500">Senha</label>
                        <input :type="showPassword ? 'text' : 'password'" v-model="payload.security.password" id="password" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-500 text-xs rounded-lg block w-full p-2 pr-10" />
                        <span @click="showPassword = !showPassword" class="absolute right-3 top-[38px] cursor-pointer text-gray-400">
                            <img v-if="!showPassword" src="@/assets/icons/showSecret.svg" alt="show_password" class="h-4 w-4 icon-light-gray">
                            <img v-else src="@/assets/icons/hideSecret.svg" alt="show_password" class="h-4 w-4 icon-light-gray">
                        </span>
                    </div>
                    <div class="m-2 relative">
                        <label for="passwordRepeat" class="block mb-3 text-xs font-medium text-gray-500">Senha</label>
                        <input :type="showPasswordRepeat ? 'text' : 'password'" v-model="payload.security.password_confirmation" id="passwordRepeat" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-500 text-xs rounded-lg block w-full p-2 pr-10" />
                        <span @click="showPasswordRepeat = !showPasswordRepeat" class="absolute right-3 top-[38px] cursor-pointer text-gray-400">
                            <img v-if="!showPasswordRepeat" src="@/assets/icons/showSecret.svg" alt="show_password" class="h-4 w-4 icon-light-gray">
                            <img v-else src="@/assets/icons/hideSecret.svg" alt="show_password" class="h-4 w-4 icon-light-gray">
                        </span>
                    </div>
                </div>
                <div class="mt-auto">
                    <button @click="updatePassword()" class="bg-green-500 p-2 px-4 text-xs rounded-md text-white font-semibold w-full">Atualizar</button>
                </div>
            </section>
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
    name: 'EnterpriseAccountView',
    components: {
        NavBarComponent,
        BreadCrumbComponent,
        LoaderComponent,
        AlertBoxComponent,
    },

    data() {
        return {
            isRequesting: false,
            showPassword: false,
            showPasswordRepeat: false,
            alert: {
                show: false,
                success: false,
                message: ''
            },

            payload: {
                id: 0,
                media: {
                    perfilImage: new URL('@/assets/icons/nophoto.svg',
                        import.meta.url).href,
                    newImage: '',
                },
                infos: {
                    name: '',
                    tradename: '',
                    cnpj: '',
                },
                security: {
                    email: '',
                    password: '',
                    password_confirmation: ''
                }
            }
        }
    },

    methods: {
        loadBreadCrumb: function () {
            return [{
                name: 'Minha Conta',
                path: '/usuario/conta'
            }];
        },
        handleImageUpload: function (event) {
            const file = event.target.files[0];
            if (file) {
                this.payload.media.perfilImage = URL.createObjectURL(file);
                this.payload.media.newImage = file;
            }
        },
        resetAlert: function () {
            this.alert = {
                show: false,
                success: false,
                message: ''
            };
        },

        updateImage: function () {
            this.resetAlert();

            if (!this.payload.media.newImage) {
                this.alert.show = true;
                this.alert.message = 'É necessário selecionar uma imagem!';
                return;
            }

            const formData = new FormData();
            formData.append('image', this.payload.media.newImage);

            this.isRequesting = true;
            api.post('enterprise/account/update/photo', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    Authorization: `Bearer ${Utils.getEnterpriseSessionToken()}`
                }
            }).then((response) => {
                this.isRequesting = false;
                const data = response.data;
                const urlImage = data.urlImage;

                this.payload.media.perfilImage = urlImage;

                this.alert.show = true;
                this.alert.success = true;
                this.alert.message = 'Foto de perfil atualizada com sucesso!';
                return;
            }).catch((error) => {
                this.isRequesting = false;
                this.alert.show = true;
                this.alert.success = false;
                this.alert.message = error.response.data.message;
                return;
            });
        },

        updateMyEnterprise: function () {
            this.resetAlert();
        },

        updateEmail: function () {
            this.resetAlert();
        },

        updatePassword: function () {
            this.resetAlert();
        },
    },

    created() {
        this.isRequesting = true;
        api.get('/enterprise/me', {
            headers: {
                Authorization: `Bearer ${Utils.getEnterpriseSessionToken()}`
            }
        }).then((response) => {
            this.isRequesting = false;
            const data = response.data;

            if (data.profile_photo) {
                this.payload.media.perfilImage = data.profile_photo;
            }

            this.payload.infos.name = data.name;
            this.payload.infos.tradename = data.tradename;
            this.payload.infos.cnpj = data.cnpj;

            this.payload.security.email = data.email;

        }).catch((error) => {
            this.isRequesting = false;
            if (error.status == 401) {
                return Utils.destroySessionWithLoginRequired();
            }
        })
    }
}
</script>
