<template>
<div :class="modalSettings.show ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'" class="fixed inset-0 z-[999] grid w-screen place-items-center bg-black bg-opacity-60 backdrop-blur-sm transition-opacity duration-300 overflow-y-auto">
    <div class="relative m-4 p-4 w-2/5 min-w-[90%] max-w-[90%] md:min-w-[40%] md:max-w-[40%] rounded-lg bg-white shadow-sm">
        <div class="flex shrink-0 items-center pb-4 text-md font-medium text-slate-800">
            Cadastrar Novo Serviço
        </div>
        <div class="relative border-t border-slate-200 py-4 leading-normal text-slate-600 font-light">
            <AlertBoxComponent :alert="alert" />

            <div v-show="isRequesting">
                <LoaderComponent />
            </div>

            <div class="max-w-3xl mx-auto space-y-4" v-show="!isRequesting">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                    <input v-model="payload.name" id="name" type="text" required class="mt-1 w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500" />
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900" for="file_input">Imagem</label>
                    <input @change="handleImageUpload" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none px-3 py-2" id="file_input" type="file">
                    <p class="mt-1 text-xs text-gray-500" id="file_input_help">SVG, PNG, JPG (MAX. 800x400px).</p>
                </div>
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Tipo de negócio</label>
                    <select v-model="payload.type" id="type" required class="mt-1 w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500">
                        <option disabled value="">Selecione</option>
                        <option value="empresa">Empresa</option>
                        <option value="local">Local</option>
                        <option value="servico">Serviço</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4">
                    <div>
                        <label for="postalCode" class="block text-sm font-medium text-gray-700">CEP</label>
                        <input @input="[searchAddressByPostalCode(), maskPostalCode()]" v-model="payload.postalCode" id="postalCode" type="text" required class="mt-1 w-full border rounded px-3 py-2" />
                    </div>
                    <div>
                        <label for="street" class="block text-sm font-medium text-gray-700">Rua</label>
                        <input v-model="payload.street" id="street" type="text" required class="mt-1 w-full border rounded px-3 py-2" :placeholder="searchingPlaceHolder" />
                    </div>
                    <div>
                        <label for="number" class="block text-sm font-medium text-gray-700">Número</label>
                        <input v-model="payload.number" id="number" type="text" required class="mt-1 w-full border rounded px-3 py-2" />
                    </div>
                    <div>
                        <label for="neighborhood" class="block text-sm font-medium text-gray-700">Bairro</label>
                        <input v-model="payload.neighborhood" id="neighborhood" type="text" required class="mt-1 w-full border rounded px-3 py-2" :placeholder="searchingPlaceHolder" />
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">Cidade</label>
                        <input v-model="payload.city" id="city" type="text" required class="mt-1 w-full border rounded px-3 py-2" :placeholder="searchingPlaceHolder" />
                    </div>
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700">Estado</label>
                        <select v-model="payload.state" id="state" required class="mt-1 w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500">
                            <option disabled value="">Selecione o estado</option>
                            <option value="AC">Acre (AC)</option>
                            <option value="AL">Alagoas (AL)</option>
                            <option value="AP">Amapá (AP)</option>
                            <option value="AM">Amazonas (AM)</option>
                            <option value="BA">Bahia (BA)</option>
                            <option value="CE">Ceará (CE)</option>
                            <option value="DF">Distrito Federal (DF)</option>
                            <option value="ES">Espírito Santo (ES)</option>
                            <option value="GO">Goiás (GO)</option>
                            <option value="MA">Maranhão (MA)</option>
                            <option value="MT">Mato Grosso (MT)</option>
                            <option value="MS">Mato Grosso do Sul (MS)</option>
                            <option value="MG">Minas Gerais (MG)</option>
                            <option value="PA">Pará (PA)</option>
                            <option value="PB">Paraíba (PB)</option>
                            <option value="PR">Paraná (PR)</option>
                            <option value="PE">Pernambuco (PE)</option>
                            <option value="PI">Piauí (PI)</option>
                            <option value="RJ">Rio de Janeiro (RJ)</option>
                            <option value="RN">Rio Grande do Norte (RN)</option>
                            <option value="RS">Rio Grande do Sul (RS)</option>
                            <option value="RO">Rondônia (RO)</option>
                            <option value="RR">Roraima (RR)</option>
                            <option value="SC">Santa Catarina (SC)</option>
                            <option value="SP">São Paulo (SP)</option>
                            <option value="SE">Sergipe (SE)</option>
                            <option value="TO">Tocantins (TO)</option>
                        </select>
                    </div>

                    <div>
                        <label for="additional" class="block text-sm font-medium text-gray-700">Complemento</label>
                        <input v-model="payload.additional" id="additional" type="text" required class="mt-1 w-full border rounded px-3 py-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Telefone</label>
                        <input @input="maskPhone()" v-model="payload.phone" id="phone" type="tel" required class="mt-1 w-full border rounded px-3 py-2" />
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                        <input v-model="payload.email" id="email" type="email" required class="mt-1 w-full border rounded px-3 py-2" />
                    </div>
                </div>
                <div>
                    <label for="website" class="block text-sm font-medium text-gray-700">Site</label>
                    <input v-model="payload.website" id="website" type="tel" required class="mt-1 w-full border rounded px-3 py-2" />
                </div>
            </div>
        </div>
        <div class="flex shrink-0 flex-wrap items-center pt-4 justify-end">
            <button @click="hiddenModal()" class="rounded-md border border-transparent py-2 px-4 text-center text-sm transition-all text-slate-600 hover:bg-slate-100 focus:bg-slate-100 active:bg-slate-100 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" type="button">
                Cancelar
            </button>
            <button @click="putNewService()" class="rounded-md bg-green-600 py-2 px-4 border border-transparent text-center text-sm text-white transition-all shadow-md hover:shadow-lg focus:bg-green-700 focus:shadow-none active:bg-green-700 hover:bg-green-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
                Cadastrar
            </button>
        </div>
    </div>
</div>
</template>

<script>
import Utils from '@/assets/scripts/Utils';
import AlertBoxComponent from '@/components/Home/AlertBoxComponent.vue';
import LoaderComponent from '@/components/Home/LoaderComponent.vue';
import api from '@/services/api';

export default {
    name: 'NewServiceModal',
    props: ['modalSettings'],
    components: {
        AlertBoxComponent,
        LoaderComponent
    },

    data() {
        return {
            isRequesting: false,
            searchingPlaceHolder: '',

            alert: {
                show: false,
                success: false,
                message: ''
            },
            payload: {
                name: '',
                image: null,
                type: '',
                postalCode: '',
                street: '',
                number: '',
                neighborhood: '',
                city: '',
                state: '',
                phone: '',
                email: '',
                additional: '',
                website: '',
            },
        }
    },

    methods: {
        maskPhone: function () {
            this.payload.phone = Utils.maskPhone(this.payload.phone);
        },
        maskPostalCode: function () {
            this.payload.postalCode = Utils.maskCep(this.payload.postalCode);
        },

        searchAddressByPostalCode: function () {
            const postalCode = Utils.keepNumbersOnly(this.payload.postalCode);

            if (postalCode.length !== 8) {
                return;
            }

            this.searchingPlaceHolder = 'Buscando...';

            api.get(`https://viacep.com.br/ws/${postalCode}/json/`)
                .then((response) => {
                    this.searchingPlaceHolder = '';
                    const data = response.data;

                    this.payload.street = data.logradouro || '';
                    this.payload.neighborhood = data.bairro || '';
                    this.payload.city = data.localidade || '';
                    this.payload.state = data.uf || '';
                })
                .catch((error) => {
                    this.searchingPlaceHolder = '';
                });

        },

        putNewService: function () {
            if (this.isRequesting) {
                return;
            }

            const token = Utils.getEnterpriseSessionToken();
            this.isRequesting = true;

            const formData = new FormData();
            for (const key in this.payload) {
                if (this.payload[key] !== null && this.payload[key] !== '') {
                    formData.append(key, this.payload[key]);
                }
            }

            api.post('/enterprise/services/register', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    Authorization: `Bearer ${token}`
                }
            }).then((response) => {
                this.isRequesting = false;

                if (response.status == 201) {
                    const service = response.data.data;
                    this.$emit('putServiceInList', service);
                    this.resetFields();

                    this.alert.show = true;
                    this.alert.success = true;
                    this.alert.message = response.data.message;
                    return;
                }

                this.alert.show = true;
                this.alert.success = false;
                this.alert.message = 'Não foi possível cadastrar seu serviço. Por favor, aguarde ou contate nosso suporte!';

            }).catch((error) => {
                this.isRequesting = false;
                this.alert.show = true;
                this.alert.success = false;

                if (error.response && [400, 401, 422].includes(error.status)) {
                    this.alert.message = error.response.data.message;
                } else {
                    this.alert.message = 'Serviço indisponível no momento. Por favor, aguarde ou contate nosso suporte!';
                }
            })
        },

        resetFields: function () {
            this.payload = {
                name: '',
                image: null,
                type: '',
                street: '',
                number: '',
                neighborhood: '',
                city: '',
                state: '',
                phone: '',
                email: '',
                additional: '',
                website: '',
            }
        },

        hiddenModal: function () {
            this.resetFields();
            this.modalSettings.show = false;
        },

        handleImageUpload: function (event) {
            const file = event.target.files[0];
            if (file) {
                this.payload.image = file;
            }
        }
    }
}
</script>
