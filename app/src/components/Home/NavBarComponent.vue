<template>
<nav class="bg-white p-4 border-b-2">
    <div class="max-w-7xl mx-auto flex flex-row">
        <router-link to="/">
            <img src="@/assets/images/sitelogo.png" alt="avaliai_logo" class="h-6 md:h-8 ml-0 md:ml-10">
        </router-link>

        <div ref="container" class="relative mx-auto w-7/12 md:w-5/12">
            <div class="flex items-center">
                <input type="text" v-model="query" @input="debouncedSearch" @keydown.down="moveSelection(1)" @keydown.up="moveSelection(-1)" @keydown.enter="selectActive" class="bg-gray-50 border border-green-600 text-gray-900 text-xs md:text-sm rounded-lg focus:ring-green-500 focus:border-green-500 w-full p-2" placeholder="O que você procura?" />
                <button @click="execSearch()" type="button" class="p-2 md:p-2.5 ms-1 text-sm font-medium text-white bg-green-700 rounded-lg border border-green-700 hover:bg-green-800">
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="none">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </button>
            </div>

            <ul v-if="results.length && showSuggestions" class="absolute z-10 w-full md:w-11/12 bg-white border border-gray-200 rounded-lg shadow-lg max-h-80 overflow-auto">
                <li v-for="(item, index) in results" :key="item.id" :class="['flex items-center p-2 cursor-pointer hover:bg-gray-100', index === activeIndex ? 'bg-gray-100' : '']" @click="navigateTo(item)" @mouseover="activeIndex = index">
                    <img :src="item.imagePath" alt="" class="w-10 h-10 object-cover rounded mr-3" />
                    <span class="text-sm text-gray-700">{{ item.name }}</span>
                </li>
            </ul>
        </div>

        <div class="flex-row hidden md:block pt-2">
            <div v-if="!isLogged">
                <router-link to="/login" class="text-green-700 border border-green-700 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2 text-center me-2">Entrar</router-link>
                <router-link to="/cadastro" class="text-white border border-green-700 bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2 text-center me-2">Cadastrar</router-link>
            </div>
            <div v-if="isLogged" ref="dropdownRef" class="relative flex flex-row items-center cursor-pointer" @click="toggleDropdown()">
                <img class="w-6 h-6 p-1 rounded-full ring-2 ring-green-700" :src="sessionData.perfilImageSrc" alt="perfil_logo">
                <span class="text-sm font-semibold ml-2">Olá, {{ sessionData.name }}!</span>

                <!-- DROPDOWN MENU -->
                <div v-if="isDropdownOpen" class="absolute right-0 top-full mt-2 w-40 bg-white border rounded-md shadow-lg z-50">
                    <router-link :to="sessionData.redirectHomeTo" class="block px-4 py-2 text-sm hover:bg-gray-100">Home</router-link>

                    <div v-if="sessionData.type == 1">
                        <router-link :to="systemRoutes.user.account" class="block px-4 py-2 text-sm hover:bg-gray-100">Minha Conta</router-link>
                        <router-link :to="systemRoutes.user.reviews" class="block px-4 py-2 text-sm hover:bg-gray-100">Avaliações</router-link>
                    </div>
                    <div v-if="sessionData.type == 2">
                        <router-link :to="systemRoutes.enterprise.account" class="block px-4 py-2 text-sm hover:bg-gray-100">Minha Conta</router-link>
                        <router-link :to="systemRoutes.enterprise.services" class="block px-4 py-2 text-sm hover:bg-gray-100">Meus Serviços</router-link>
                        <router-link :to="systemRoutes.enterprise.reviews" class="block px-4 py-2 text-sm hover:bg-gray-100">Avaliações</router-link>
                    </div>

                    <button @click="logout()" class="block w-full text-left px-4 py-2 text-sm font-semibold text-red-600 hover:bg-gray-100 mt-4">Sair</button>
                </div>
            </div>

        </div>
        <div class="block md:hidden mt-1" @click="manageSideNav()">
            <img src="@/assets/icons/menu.svg" alt="menu_login" class="h-7">
        </div>
    </div>
</nav>

<aside class="fixed top-18 left-0 w-full h-[calc(100vh-4rem)] bg-gray-50 z-10 transition-transform duration-300 ease-in-out" :class="isSideNavOpen ? 'translate-x-0' : 'translate-x-full'">
    <div class="p-3 text-center w-full">
        <img src="@/assets/icons/close.svg" alt="close_icon" class="h-8" @click="manageSideNav()">
    </div>
    <div class="flex flex-row w-full justify-center content-center">
        <div v-if="!isLogged">
            <router-link to="/login" class="text-green-700 border border-green-700 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2 text-center me-2">Entrar</router-link>
            <router-link to="/cadastro" class="text-white border border-green-700 bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2 text-center me-2">Cadastrar</router-link>
        </div>
        <div v-if="isLogged">
            <div class="flex flex-row">
                <img class="w-6 h-6 p-1 rounded-full ring-2 ring-green-700" :src="sessionData.perfilImageSrc" alt="perfil_logo">
                <span class="text-sm font-semibold ml-2 mt-1">Olá, {{ sessionData.name }}!</span>
            </div>
            <hr class="mt-5">
            <div class="w-full mt-3 text-center">
                <div v-if="sessionData.type == 1">
                    <router-link :to="systemRoutes.user.account" class="block px-4 py-2 text-sm hover:bg-gray-100">Minha Conta</router-link>
                    <router-link :to="systemRoutes.user.reviews" class="block px-4 py-2 text-sm hover:bg-gray-100">Avaliações</router-link>
                </div>
                <div v-if="sessionData.type == 2">
                    <router-link :to="systemRoutes.enterprise.account" class="block px-4 py-2 text-sm hover:bg-gray-100">Minha Conta</router-link>
                    <router-link :to="systemRoutes.enterprise.services" class="block px-4 py-2 text-sm hover:bg-gray-100">Meus Serviços</router-link>
                    <router-link :to="systemRoutes.enterprise.reviews" class="block px-4 py-2 text-sm hover:bg-gray-100">Avaliações</router-link>
                </div>
                <button @click="logout()" class="block w-full px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Sair</button>
            </div>
        </div>
    </div>
</aside>
</template>

<script>
import localBase from '@/assets/scripts/LocalBase';
import Utils from '@/assets/scripts/Utils';
import router from '@/router';
import api from '@/services/api';

export default {
    name: 'NavBarComponent',

    data() {
        return {
            isSideNavOpen: false,
            isLogged: false,
            isDropdownOpen: false,
            dropdownRef: null,

            query: '',
            results: [],
            activeIndex: -1,
            showSuggestions: false,

            sessionData: {
                name: '',
                type: 0,
                perfilImageSrc: null,
                redirectHomeTo: ''
            },
            systemRoutes: {
                enterprise: {
                    account: '/empresa/conta',
                    services: '/empresa/servicos',
                    reviews: '/empresa/avaliacoes'
                },
                user: {
                    account: '/usuario/conta',
                    reviews: '/usuario/avaliacoes'
                }
            }
        }
    },

    methods: {
        manageSideNav: function () {
            this.isSideNavOpen = !this.isSideNavOpen;
        },

        toggleDropdown: function () {
            this.isDropdownOpen = !this.isDropdownOpen;
        },

        closeDropdown: function (event) {
            if (this.dropdownRef && !this.dropdownRef.contains(event.target)) {
                this.isDropdownOpen = false;
            }
        },

        execSearch: function() {
            router.push({
                path: `/search/${Utils.filterWord(this.query)}`
            });
        },

        debouncedSearch() {
            clearTimeout(this.debounceTimeout)
            this.debounceTimeout = setTimeout(() => {
                this.onSearch()
            }, 300)
        },

        onSearch() {
            if (this.query.length < 3) {
                this.results = []
                this.showSuggestions = false
                return
            }

            try {
                api.get(`/search?term=${encodeURIComponent(this.query)}`, {})
                    .then((response) => {
                        this.results = response.data.data;
                        this.showSuggestions = true
                        this.activeIndex = -1
                    }).catch((error) => {
                        this.results = []
                        this.showSuggestions = false
                    });
            } catch (error) {
                this.results = []
                this.showSuggestions = false
            }
        },

        navigateTo(item) {
            if (item.type === 1) {
                router.push({
                    path: `/empresa/${Utils.filterWord(item.name)}/${item.id}`
                });
            } else if (item.type === 2) {
                router.push({
                    path: `/empresa/item/${Utils.filterWord(item.name)}/${item.id}`
                });
            }
            this.reset()
        },

        moveSelection(direction) {
            if (!this.results.length) return
            const total = this.results.length
            this.activeIndex = (this.activeIndex + direction + total) % total
        },

        selectActive() {
            if (this.activeIndex >= 0 && this.results[this.activeIndex]) {
                this.navigateTo(this.results[this.activeIndex])
            }
        },

        reset() {
            this.query = ''
            this.results = []
            this.activeIndex = -1
            this.showSuggestions = false
        },

        logout: function () {
            Utils.logoutApi();
            Utils.destroySession();
        },
        handleClickOutside(event) {
            if (!this.$refs.container.contains(event.target)) {
                this.reset()
            }
        },
    },

    mounted() {
        document.addEventListener('click', this.handleClickOutside)
    },
    beforeUnmount() {
        document.removeEventListener('click', this.handleClickOutside)
    },

    created() {
        let sessionInfo = null;

        const userData = localBase.select(localBase.keys.login.user);
        const enterpriseData = localBase.select(localBase.keys.login.enteprise);

        try {
            if (userData) {
                sessionInfo = JSON.parse(userData);
            } else if (enterpriseData) {
                sessionInfo = JSON.parse(enterpriseData);
            }
        } catch (e) {
            this.isLogged = false;
            return;
        }

        if (sessionInfo && sessionInfo.token) {
            this.isLogged = true;
            this.sessionData.type = sessionInfo.user.type;

            switch (sessionInfo.user.type) {
                case 1:
                    this.sessionData.name = sessionInfo.user.name;
                    this.sessionData.perfilImageSrc = sessionInfo.user.profile_photo || new URL('@/assets/icons/person.svg',
                        import.meta.url).href;
                    this.sessionData.redirectHomeTo = '/usuario/home';
                    break;
                case 2:
                    this.sessionData.name = sessionInfo.user.tradename;
                    this.sessionData.perfilImageSrc = sessionInfo.user.profile_photo || new URL('@/assets/icons/company.svg',
                        import.meta.url).href;
                    this.sessionData.redirectHomeTo = '/empresa/home';
                    break;
                default:
                    break;
            }
        } else {
            this.isLogged = false;
        }
    },
}
</script>
