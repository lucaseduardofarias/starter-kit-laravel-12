<template>
    <nav class="bg-white shadow-md rounded-lg p-4">
        <div class="flex items-center justify-between">
            <!-- Título -->
            <div>
                <h4>
                    <a href="/" title="Reiniciar aplicação" class="text-xl font-semibold">
                        {{ title }}
                    </a>
                </h4>
            </div>

            <div class="w-full sm:w-auto">
                <div class="flex items-center justify-end space-x-4 flex-nowrap">
                    <input
                        v-model="cpf_cnpj"
                        v-imask="{
                          mask: [
                            { mask: '000.000.000-00' },
                            { mask: '00.000.000/0000-00' }
                          ],
                          dispatch: function (appended, dynamicMasked) {
                            const onlyNumbers = (dynamicMasked.value + appended).replace(/\D+/g, '');
                            return onlyNumbers.length > 11 ? dynamicMasked.compiledMasks[1]: dynamicMasked.compiledMasks[0];
                          }
                        }"
                        :disabled="loading || cpf_cnpj && client ? true : false"
                        type="text"
                        class="shadow border rounded px-2 py-1 w-60"
                        placeholder="Informe o seu CPF ou CNPJ"
                    />

                    <button
                        @click="fetchClient"
                        v-if="!loading && !client"
                        type="button"
                        class="bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-50"
                    >
                        Entrar / Cadastrar
                    </button>

                    <button
                        v-if="loading"
                        type="button"
                        class="bg-gray-500 text-white px-4 py-2 rounded opacity-75 cursor-wait"
                        disabled
                    >
                        Processando...
                    </button>

                    <!-- Botão de encerrar -->
                    <button
                        @click="resetClient"
                        v-if="!loading && cpf_cnpj && client"
                        type="button"
                        class="bg-red-600 text-white px-4 py-2 rounded disabled:opacity-50"
                    >
                        Encerrar Acesso
                    </button>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
import bus from '../eventBus';
import _ from 'lodash';

export default {
    name: 'HeaderVue',
    props: ['title'],

    data() {
        return {
            loading: false,
            cpf_cnpj: '',
            client: null,
        };
    },

    watch: {
        cpf_cnpj: _.debounce(function () {
            this.cpf_cnpj = this.cpf_cnpj.trim();
            if (!this.cpf_cnpj) {
                this.fetchClient();
            }
        }, 300),
    },

    mounted() {
        const savedClient = localStorage.getItem('myAppClient');
        if (savedClient) {
            try {
                this.client = JSON.parse(savedClient);
                this.cpf_cnpj = this.client.cpf_cnpj;
                this.emitSetClient();
            } catch (e) {
                console.error('Falha ao analisar client do localStorage', e);
                localStorage.removeItem('myAppClient');
            }
        }
    },

    methods: {
        resetClient() {
            this.cpf_cnpj = '';
            this.client = null;
            localStorage.removeItem('myAppClient');
            this.emitSetClient();
        },

        emitSetClient() {
            bus.emit('set-client', this.client);
        },

        fetchClient() {
            if (!this.cpf_cnpj) {
                this.client = null;
                this.emitSetClient();
            } else {
                this.loading = true;

                axios
                    .get('/api/asass/client/search/' + this.cpf_cnpj)
                    .then((response) => {
                        this.client = response.data.data;
                        this.cpf_cnpj = this.client.cpf_cnpj;
                        localStorage.setItem('myAppClient', JSON.stringify(this.client));
                        this.emitSetClient();
                    })
                    .catch(() => {
                        axios
                            .post('/api/asass/client', { cpf_cnpj: this.cpf_cnpj })
                            .then((response) => {
                                this.client = response.data.data;
                                this.cpf_cnpj = this.client.cpf_cnpj;
                                localStorage.setItem('myAppClient', JSON.stringify(this.client));
                                this.emitSetClient();
                            })
                            .catch((error) => {
                                this.handleApiError(error);
                                this.client = null;
                                this.emitSetClient();
                            });
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            }
        },

        handleApiError(error) {
            const resp = error.response;
            if (resp && resp.data) {
                const { errors } = resp.data;
                let msg = '';

                if (errors && typeof errors === 'object') {
                    const details = Object.values(errors)
                        .flat()
                        .filter(Boolean)
                        .join('\n');
                    if (details) {
                        msg += '\n\n' + details;
                    }
                }
                alert(msg);
            } else {
                alert('Erro desconhecido. Verifique sua conexão e tente novamente.');
            }
        },
    },
};
</script>

<style scoped>
.title {
    padding-top: 0.4rem;
}
</style>
