<template>
<section class="min-h-screen flex flex-col">
    <NavBarComponent />

    <section class="flex-grow bg-gray-50 border-b-2 flex items-center justify-center">
        <!-- Select account type -->
        <div v-show="formType == 0">
            <div class="text-center mt-10">
                <h3 class="text-2xl font-bold">Entrar no Avalia Ai!</h3>
                <h6 class="text-sm font-medium mt-1">Para começar, selecione o seu tipo de perfil: </h6>
            </div>

            <div class="md:flex flex-row justify-center mt-5">
                <!-- User Account -->
                <div @click="setFormType(1)" class="hover:bg-green-50 pb-5 flex flex-col items-center border-2 m-3 rounded-md p-3 w-80 cursor-pointer" :class="(formType == 1 ? 'bg-green-50' : '')">
                    <img src="@/assets/icons/person.svg" alt="person_icon" class="h-16 border bg-green-50 rounded-full p-2 border-green-400 mb-2">
                    <h3 class="text-center font-bold text-green-800">Usuário</h3>
                    <p class="text-sm text-green-900 text-center mt-4">
                        Avalie e consulte locais, estabelecimentos, empresas, serviços e muito mais!
                    </p>
                </div>

                <!-- Enterprise Account -->
                <div @click="setFormType(2)" class="hover:bg-green-50 pb-5 flex flex-col items-center border-2 m-3 rounded-md p-3 w-80 cursor-pointer" :class="(formType == 2 ? 'bg-green-50' : '')">
                    <img src="@/assets/icons/company.svg" alt="company_icon" class="h-16 border bg-green-50 rounded-full p-2 border-green-400 mb-2">
                    <h3 class="text-center font-bold text-green-800">Empresa/Órgão</h3>
                    <p class="text-sm text-green-900 text-center mt-4">
                        Receba avaliações e feedbacks sobre seu negócio, empresa, órgão ou local!
                    </p>
                </div>
            </div>
        </div>

        <div v-show="showForm()" class="mb-10 p-4 md:p-0 md:w-4/12">
            <div class="text-center mt-10">
                <h3 class="text-2xl font-bold">Entrar no Avalia Ai!</h3>
                <h6 class="text-sm font-medium mt-1">Para entrar, digite suas credenciais abaixo: </h6>
            </div>

            <AlertBoxComponent :alert="alert" />
            <div v-if="isRequesting">
                <LoaderComponent />
            </div>

            <div class="flex mt-5" v-show="!isRequesting">
                <div class="w-full border-2 p-3 rounded-md">
                    <div class="mb-5 m-2">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                        <input v-model="email" type="text" id="email" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2" required />
                    </div>
                    <div class="mb-5 m-2">
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Senha</label>
                        <input v-model="password" type="password" id="password" class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2" required />
                    </div>
                    <div class="mb-5 m-2">
                        <div class="w-full text-center mt-2">
                            <button @click="login()" type="button" class="bg-green-800 text-center text-white text-sm rounded-md p-2 pl-3 pr-3 w-36 md:w-56">Entrar</button>
                        </div>
                    </div>
                    <span @click="setFormType(0)" class="text-sm font-semibold cursor-pointer">Voltar</span>
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
import LoaderComponent from '@/components/Home/LoaderComponent.vue';
import api from '@/services/api';
import localBase from '@/assets/scripts/LocalBase';
import router from '@/router';
import {
    useRoute
} from 'vue-router';

export default {
    name: 'LoginView',

    components: {
        NavBarComponent,
        FooterComponent,
        AlertBoxComponent,
        LoaderComponent,
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

            email: '',
            password: ''
        }
    },

    methods: {
        setFormType: function (type) {
            this.formType = type;
            this.showForm();
            this.clearAlert();
        },

        showForm: function () {
            return [1, 2].includes(this.formType);
        },

        clearAlert: function () {
            this.alert.show = false;
            this.alert.success = false;
            this.alert.message = '';
        },

        login: function () {
            this.clearAlert();

            if (!this.formType) {
                this.alert.show = true;
                this.alert.success = false;
                this.alert.message = 'Por favor, Selecione o seu tipo de perfil';
            }

            let url = '';
            let loginKey = null;
            let redirectTo = '';

            if (this.formType == 1) {
                url = '/user/login';
                loginKey = localBase.keys.login.user;
                redirectTo = '/usuario/home';
            }
            if (this.formType == 2) {
                url = '/enterprise/login';
                loginKey = localBase.keys.login.enteprise;
                redirectTo = '/empresa/home';
            }

            this.isRequesting = true;
            api.post(url, {
                email: this.email,
                password: this.password,
            }).then((response) => {
                this.isRequesting = false;

                if (response.status == 200) {
                    localBase.remove(localBase.keys.login.user);
                    localBase.remove(localBase.keys.login.enteprise);

                    const userInfo = response.data;
                    localBase.insert(loginKey, JSON.stringify(userInfo));
                    router.push({
                        path: redirectTo
                    })
                    return;
                }

                this.alert.show = true;
                this.alert.success = false;
                this.alert.message = 'Não foi possível realizar o login. Por favor, aguarde ou contate nosso suporte!';

            }).catch((error) => {
                this.isRequesting = false;
                this.alert.show = true;
                this.alert.success = false;

                if (error.response && [400, 401].includes(error.status)) {
                    this.alert.message = error.response.data.message;
                } else {
                    this.alert.message = 'Serviço indisponível no momento. Por favor, aguarde ou contate nosso suporte!';
                }
            })
        },

        checkExpiredSession: function () {
            const route = useRoute();
            const isExpired = route.query.expired;

            if (isExpired) {
                const typeAccount = route.query.type;
                switch (typeAccount) {
                    case 'user':
                        this.setFormType(1);
                        this.alert.show = true;
                        this.alert.success = false;
                        this.alert.message = 'Sessão Expirada. Por favor, faça login novamente.';
                        break;

                    case 'enterprise':
                        this.setFormType(2);
                        this.alert.show = true;
                        this.alert.success = false;
                        this.alert.message = 'Sessão Expirada. Por favor, faça login novamente.';
                        break;

                    default:
                        break;
                }
            }
        },

        checkUserAlreadyLogged: function () {
            const userSession = JSON.parse(
                localBase.select(localBase.keys.login.user)
            );
            const enterpriseSession = JSON.parse(
                localBase.select(localBase.keys.login.enteprise)
            );

            if (userSession) {
                return router.push({
                    path: '/usuario/home'
                });
            }
            if (enterpriseSession) {
                return router.push({
                    path: '/empresa/home'
                });
            }
        }
    },

    created() {
        this.checkExpiredSession();
        this.checkUserAlreadyLogged();
    }
}
</script>
