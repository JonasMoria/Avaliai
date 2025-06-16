<template>
<section class="flex flex-nowrap border-b mb-4">
    <div class="flex px-2 py-2 text-gray-700 w-10/12" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <router-link :to="homePath" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-gray-700">
                    <img src="@/assets/icons/home.svg" alt="home" width="15" class="mr-1">
                    <span class="text-xs md:text-sm mt-1">Home</span>
                </router-link>
            </li>
            <li class="inline-flex items-center" v-for="(item, index) in breadcrumb" :key="index">
                <router-link :to="item.path" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-gray-700">
                    <svg class="rtl:rotate-180 block w-3 h-3 mx-1 text-gray-400 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                    </svg>
                    <span class="text-xs md:text-sm mt-1">{{ item.name }}</span>
                </router-link>
            </li>
        </ol>
        <hr>
    </div>
</section>
</template>

<script>
import localBase from '@/assets/scripts/LocalBase';

export default {
    name: 'BreadCrumbComponent',
    props: ['breadcrumb'],

    data() {
        return {
            homePath: ''
        }
    },

    mounted() {
        let sessionData = localBase.select(localBase.keys.login.user);
        if (!sessionData) {
            sessionData = localBase.select(localBase.keys.login.enteprise);
        }

        const userData = JSON.parse(sessionData);
        switch (userData.user.type) {
            case 1:
                this.homePath = '/usuario/home';
                break;

            case 2:
                this.homePath = '/empresa/home';
            break;

            default:
                break;
        }
    }
}
</script>
