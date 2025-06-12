<template>
<section class="min-h-screen flex flex-col">
    <NavBarComponent />

    <section class="flex-grow">

    </section>

    <FooterComponent />
</section>
</template>

<script>
import NavBarComponent from '@/components/Home/NavBarComponent.vue';
import FooterComponent from '@/components/Home/FooterComponent.vue';

export default {
    name: 'HomeView',

    components: {
        NavBarComponent,
        FooterComponent
    }
}
</script>
