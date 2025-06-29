<template>
<div class="flex flex-col items-center gap-6">
    <img src="@/assets/images/not_found.png" alt="content_not_found" class="max-w-xs">
    <button @click="backHome()" class="text-green-600 font-semibold border border-green-400 text-lg px-8 py-3 rounded-md shadow hover:bg-green-500 hover:text-white">
        Voltar
    </button>
</div>
</template>

<script>
import router from '@/router';

export default {
    name: 'NotFoundAlertComponent',

    methods: {
        backHome: function () {
            router.push({
                path: `/`
            });
        },
    }
}
</script>
