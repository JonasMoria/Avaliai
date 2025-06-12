<template>
<div v-show="alert.show" class="p-2 text-sm text-white text-center font-semibold rounded-md" :class="alert.success ? 'bg-green-400' : 'bg-red-400'">
    {{ (alert.success ? '' : 'Ops!') }} {{ alert.message }}
</div>
</template>

<script>
export default {
    name: 'AlertBoxComponent',
    props: ['alert'],
}
</script>
