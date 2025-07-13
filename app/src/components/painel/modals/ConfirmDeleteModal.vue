<template>
<div :class="modalConfirmSettings.show ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'" class="fixed inset-0 z-[999] grid w-screen place-items-center bg-black bg-opacity-60 backdrop-blur-sm transition-opacity duration-300 overflow-y-auto">
    <div class="relative m-4 p-4 w-2/5 min-w-[90%] max-w-[90%] md:min-w-[40%] md:max-w-[40%] rounded-lg bg-white shadow-sm">
        <div class="flex justify-end p-2">
            <button @click="hiddenModal()" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>

        <div class="p-6 pt-0 text-center">
            <svg class="w-20 h-20 text-red-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-xl font-normal text-gray-500 mt-5 mb-6">{{ modalConfirmSettings.alertMessage }}</h3>
            <button @click="confirmAction()" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-base inline-flex items-center px-3 py-2.5 text-center mr-2">
                Sim, remover
            </button>
            <button @click="hiddenModal()" class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-cyan-200 border border-gray-200 font-medium inline-flex items-center rounded-lg text-base px-3 py-2.5 text-center">
                Não, cancelar
            </button>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: 'ConfirmDeleteModal',
    props: ['modalConfirmSettings', 'confirmDelete'],

    methods: {
        hiddenModal: function () {
            this.modalConfirmSettings.show = false;
        },

        confirmAction: function () {
            this.confirmDelete(this.modalConfirmSettings.id, true);
        }
    }
}
</script>
