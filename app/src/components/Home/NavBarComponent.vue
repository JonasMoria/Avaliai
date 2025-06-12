<template>
<nav class="bg-white p-4 border-b-2">
    <div class="max-w-7xl mx-auto flex flex-row">
        <router-link to="/">
            <img src="@/assets/images/sitelogo.png" alt="avaliai_logo" class="h-6 md:h-8 ml-0 md:ml-10">
        </router-link>

        <div class="flex items-center mx-auto w-7/12 md:w-5/12">
            <div class="relative w-full">
                <input type="text" id="simple-search" class="bg-gray-50 border border-green-600 text-gray-900 text-xs md:text-sm rounded-lg focus:ring-green-500 focus:border-green-500 w-full p-2" placeholder="O que você procura?" />
            </div>
            <button type="submit" class="p-2 md:p-2.5 ms-1 text-sm font-medium text-white bg-green-700 rounded-lg border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
                <span class="sr-only">Search</span>
            </button>
        </div>

        <div class="flex-row hidden md:block pt-2">
            <router-link to="/login" class="text-green-700 border border-green-700 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2 text-center me-2">Entrar</router-link>
            <router-link to="/cadastro" class="text-white border border-green-700 bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2 text-center me-2">Cadastrar</router-link>
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
        <router-link to="/login" class="text-green-700 border border-green-700 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2 text-center me-2">Entrar</router-link>
        <router-link to="/cadastro" class="text-white border border-green-700 bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2 text-center me-2">Cadastrar</router-link>
    </div>
</aside>
</template>

<script>
export default {
    name: 'NavBarComponent',

    data() {
        return {
            isSideNavOpen: false,
        }
    },

    methods: {
        manageSideNav: function () {
            this.isSideNavOpen = !this.isSideNavOpen;
        }
    }
}
</script>
