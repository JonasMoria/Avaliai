<template>
<section class="min-h-screen flex flex-col">
    <NavBarComponent />

    <section class="flex-grow bg-gray-50 border-b-2 flex items-center justify-center">
        <!-- Select account type -->
        <div v-show="formType == 0">
            <div class="text-center mt-10">
                <h3 class="text-2xl font-bold">Crie uma conta no Avalia Ai!</h3>
                <h6 class="text-sm font-medium mt-1">Para começar, escolha o seu tipo de perfil: </h6>
            </div>

            <div class="md:flex flex-row justify-center mt-5">
                <!-- User Account -->
                <div @click="setFormType(1)" class="hover:bg-green-50 pb-5 flex flex-col items-center border-2 m-3 rounded-md p-3 w-80 cursor-pointer" :class="(payload.accountType == 1 ? 'bg-green-50' : '')">
                    <img src="@/assets/icons/person.svg" alt="person_icon" class="h-16 border bg-green-50 rounded-full p-2 border-green-400 mb-2">
                    <h3 class="text-center font-bold text-green-800">Usuário</h3>
                    <p class="text-sm text-green-900 text-center mt-4">
                        Avalie e consulte locais, estabelecimentos, empresas, serviços e muito mais!
                    </p>
                </div>

                <!-- Enterprise Account -->
                <div @click="setFormType(2)" class="hover:bg-green-50 pb-5 flex flex-col items-center border-2 m-3 rounded-md p-3 w-80 cursor-pointer" :class="(payload.accountType == 2 ? 'bg-green-50' : '')">
                    <img src="@/assets/icons/company.svg" alt="company_icon" class="h-16 border bg-green-50 rounded-full p-2 border-green-400 mb-2">
                    <h3 class="text-center font-bold text-green-800">Empresa/Órgão</h3>
                    <p class="text-sm text-green-900 text-center mt-4">
                        Receba avaliações e feedbacks sobre seu negócio, empresa, órgão ou local!
                    </p>
                </div>
            </div>
        </div>

        <!-- User Form -->
        <div v-show="formType == 1" class="mb-10 p-4 md:p-0">
            <div class="text-center mt-10 mb-3">
                <h3 class="text-2xl font-bold">Crie uma conta no Avalia Ai!</h3>
                <h6 class="text-sm font-medium mt-1">Por favor, preencha o formulário abaixo: </h6>
            </div>

            <AlertBoxComponent :alert="alert" />
            <div v-if="isRequesting">
                <LoaderComponent />
            </div>

            <div class="flex mt-5" v-show="!isRequesting">
                <div class="border-2 p-3 rounded-md">
                    <div class="w-full flex flex-row">
                        <div class="m-2 w-6/12">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nome</label>
                            <input v-model="payload.user.name" type="text" id="name" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2" required />
                        </div>
                        <div class="m-2 w-6/12">
                            <label for="surname" class="block mb-2 text-sm font-medium text-gray-900">Sobrenome</label>
                            <input v-model="payload.user.surname" type="text" id="surname" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2" required />
                        </div>
                    </div>
                    <div class="mb-5 m-2">
                        <label for="cpf" class="block mb-2 text-sm font-medium text-gray-900">CPF</label>
                        <input @input="maskCpf()" v-model="payload.user.cpf" type="text" id="cpf" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2" required />
                    </div>
                    <div class="mb-5 m-2">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                        <input v-model="payload.user.email" type="text" id="email" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2" required />
                    </div>
                    <div class="mb-5 m-2">
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Senha</label>
                        <input v-model="payload.user.password" type="password" id="password" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2" required />
                    </div>
                    <div class="mb-5 m-2">
                        <label for="repeat-pass" class="block mb-2 text-sm font-medium text-gray-900">Repita sua senha</label>
                        <input v-model="payload.user.password_confirmation" type="password" id="repeat-pass" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2" required />
                    </div>

                    <div class="mb-5 m-2">
                        <div class="flex items-start mb-5">
                            <div class="flex items-center h-3">
                                <input v-model="payload.agreeTerms" id="terms" type="checkbox" class="w-3 h-3 border border-gray-300 rounded-sm bg-gray-50 focus:ring-3 focus:ring-green-300" required />
                            </div>
                            <label for="terms" class="ms-2 text-xs font-medium text-gray-900">Eu li e concordo com os <router-link to="/" class="text-green-700 hover:underline ">termos e condições</router-link></label>
                        </div>
                        <div class="w-full text-center mt-2">
                            <button @click="registerUser()" type="button" class="bg-green-800 text-center text-white text-sm rounded-md p-2 pl-3 pr-3">Cadastrar</button>
                        </div>
                    </div>

                    <span @click="backForm()" class="text-sm font-semibold cursor-pointer">Voltar</span>
                </div>
            </div>
        </div>

        <div v-show="formType == 2" class="justify-center mb-10 p-4 md:p-0">
            <div class="text-center mt-10 mb-3">
                <h3 class="text-2xl font-bold">Crie uma conta no Avalia Ai!</h3>
                <h6 class="text-sm font-medium mt-1">Por favor, preencha o formulário abaixo: </h6>
            </div>

            <AlertBoxComponent :alert="alert" />
            <div v-if="isRequesting">
                <LoaderComponent />
            </div>

            <div class="flex mt-5" v-show="!isRequesting">
                <div class="border-2 p-3 rounded-md">
                    <div class="w-full flex flex-row">
                        <div class="m-2 w-6/12">
                            <label for="enterprise_name" class="block mb-2 text-sm font-medium text-gray-900">Razão Social</label>
                            <input v-model="payload.enterprise.name" type="text" id="enterprise_name" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2" required />
                        </div>
                        <div class="m-2 w-6/12">
                            <label for="tradename" class="block mb-2 text-sm font-medium text-gray-900">Nome Fantasia</label>
                            <input v-model="payload.enterprise.tradename" type="text" id="tradename" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2" required />
                        </div>
                    </div>
                    <div class="mb-5 m-2">
                        <label for="cnpj" class="block mb-2 text-sm font-medium text-gray-900">CNPJ</label>
                        <input @input="maskCnpj()" v-model="payload.enterprise.cnpj" type="text" id="cnpj" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2" required />
                    </div>
                    <div class="mb-5 m-2">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                        <input v-model="payload.enterprise.email" type="text" id="enterprise_email" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2" required />
                    </div>
                    <div class="mb-5 m-2">
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Senha</label>
                        <input v-model="payload.enterprise.password" type="password" id="enterprise_password" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2" required />
                    </div>
                    <div class="mb-5 m-2">
                        <label for="repeat-pass" class="block mb-2 text-sm font-medium text-gray-900">Repita sua senha</label>
                        <input v-model="payload.enterprise.password_confirmation" type="password" id="enterprise_repeat-pass" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2" required />
                    </div>
                    <div class="mb-5 m-2">
                        <div class="flex items-start mb-5">
                            <div class="flex items-center h-3">
                                <input v-model="payload.agreeTerms" id="enterprise_terms" type="checkbox" class="w-3 h-3 border border-gray-300 rounded-sm bg-gray-50 focus:ring-3 focus:ring-green-300" required />
                            </div>
                            <label for="enterprise_terms" class="ms-2 text-xs font-medium text-gray-900">Eu li e concordo com os <router-link to="/" class="text-green-700 hover:underline ">termos e condições</router-link></label>
                        </div>
                        <div class="w-full text-center mt-2">
                            <button @click="registerEnterprise()" type="button" class="bg-green-800 text-center text-white text-sm rounded-md p-2 pl-3 pr-3">Cadastrar</button>
                        </div>
                    </div>

                    <span @click="backForm()" class="text-sm font-semibold cursor-pointer">Voltar</span>
                </div>
            </div>
        </div>
    </section>

    <FooterComponent />
</section>
</template>

<script>
import NavBarComponent from '@/components/Home/NavBarComponent.vue';
import FooterComponent from '@/components/Home/FooterComponent.vue';
import AlertBoxComponent from '@/components/Home/AlertBoxComponent.vue';

import Utils from '@/assets/scripts/Utils';
import api from '@/services/api';
import LoaderComponent from '@/components/Home/LoaderComponent.vue';

export default {
    name: 'RegisterView',

    components: {
        NavBarComponent,
        FooterComponent,
        AlertBoxComponent,
        LoaderComponent
    },

    data() {
        return {
            formType: 0,
            isRequesting: false,

            alert: {
                show: false,
                success: false,
                message: ''
            },

            payload: {
                accountType: 0,
                agreeTerms: false,
                user: {
                    name: '',
                    surname: '',
                    cpf: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                },
                enterprise: {
                    name: '',
                    tradename: '',
                    cnpj: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                }
            }
        }
    },

    methods: {
        setFormType: function (type) {
            this.formType = type;
            this.payload.accountType = type;
            this.resetAlert();
        },
        backForm: function () {
            this.formType = 0;
        },
        maskCpf: function () {
            this.payload.user.cpf = Utils.maskCpf(this.payload.user.cpf);
        },
        maskCnpj: function () {
            this.payload.enterprise.cnpj = Utils.maskCnpj(this.payload.enterprise.cnpj);
        },

        resetAlert: function () {
            this.alert = {
                show: false,
                success: false,
                message: ''
            };
        },

        checkTermsAccepted: function () {
            if (!this.payload.agreeTerms) {
                this.alert.show = true;
                this.alert.success = false;
                this.alert.message = 'É necessário aceitar os termos e condições de uso!';
                return false;
            }

            return true;
        },

        registerUser: function () {
            this.resetAlert();

            if (!this.checkTermsAccepted()) {
                return;
            }

            this.isRequesting = true;
            api.post('/user/register', this.payload.user)
                .then((response) => {
                    this.isRequesting = false;
                    this.alert.show = true;
                    this.alert.success = true;
                    this.alert.message = response.data.message;
                    return;

                }).catch((error) => {
                    this.isRequesting = false;
                    this.alert.show = true;
                    this.alert.success = false;

                    if (error.response && error.response.status == 422) {
                        this.alert.message = error.response.data.message;
                    } else {
                        this.alert.message = 'Serviço indisponível no momento. Por favor, aguarde ou contate nosso suporte!';
                    }
                });
        },

        registerEnterprise: function () {
            this.resetAlert();

            if (!this.checkTermsAccepted()) {
                return;
            }

            this.isRequesting = true;
            api.post('/enterprise/register', this.payload.enterprise)
                .then((response) => {
                    this.isRequesting = false;
                    this.alert.show = true;
                    this.alert.success = true;
                    this.alert.message = response.data.message;
                    return;
                })
                .catch((error) => {
                    this.isRequesting = false;
                    this.alert.show = true;
                    this.alert.success = false;

                    if (error.response && error.response.status == 422) {
                        this.alert.message = error.response.data.message;
                    } else {
                        this.alert.message = 'Serviço indisponível no momento. Por favor, aguarde ou contate nosso suporte!';
                    }
                });
        }
    }
}
</script>
