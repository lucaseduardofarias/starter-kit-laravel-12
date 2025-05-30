<template>
    <form class="space-y-3">
        <div class="mb-4" v-if="!disabled">
            <label class="block text-sm">CPF / CNPJ</label>
            <input
                v-model="form.cpf_cnpj"
                type="text"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            />
        </div>
        <div class="mb-4" v-else>
            <label class="block text-sm">CPF / CNPJ</label>
            {{ form.cpf_cnpj }}
        </div>

        <div class="mb-4" v-if="!disabled">
            <label class="block text-sm">Nome </label>
            <input
                v-model="form.name"
                type="text"
                class="mt-1 w-full border rounded px-2 py-1"
            />
        </div>
        <div class="mb-4" v-else>
            <label class="block text-sm">Nome</label>
            {{ form.name }}
        </div>

        <div class="mb-4" v-if="!disabled">
            <label class="block text-sm">Email </label>
            <input
                v-model="form.email"
                type="text"
                class="mt-1 w-full border rounded px-2 py-1"
            />
        </div>
        <div class="mb-4" v-else>
            <label class="block text-sm">Email</label>
            {{ form.email }}
        </div>

        <div class="mb-4" v-if="!disabled">
            <label class="block text-sm">Celular </label>
            <input
                v-model="form.phone"
                type="text"
                class="mt-1 w-full border rounded px-2 py-1"
            />
        </div>
        <div class="mb-4" v-else>
            <label class="block text-sm">Celular</label>
            {{ form.phone }}
        </div>

        <div class="mb-4" v-if="!disabled">
            <label class="block text-sm">CEP </label>
            <input
                v-model="form.postal_code"
                type="text"
                class="mt-1 w-full border rounded px-2 py-1"
            />
        </div>
        <div class="mb-4" v-else>
            <label class="block text-sm">CEP</label>
            {{ form.postal_code }}
        </div>

        <div class="mb-4" v-if="!disabled">
            <label class="block text-sm">Endereço </label>
            <input
                v-model="form.address"
                type="text"
                class="mt-1 w-full border rounded px-2 py-1"
            />
        </div>
        <div class="mb-4" v-else>
            <label class="block text-sm">Endereço</label>
            {{ form.address }}
        </div>

        <div class="mb-4" v-if="!disabled">
            <label class="block text-sm">Bairro </label>
            <input
                v-model="form.province"
                type="text"
                class="mt-1 w-full border rounded px-2 py-1"
            />
        </div>
        <div class="mb-4" v-else>
            <label class="block text-sm">Bairro</label>
            {{ form.province }}
        </div>

        <div class="mb-4" v-if="!disabled">
            <label class="block text-sm">Número </label>
            <input
                v-model="form.address_number"
                type="text"
                class="mt-1 w-full border rounded px-2 py-1"
            />
        </div>
        <div class="mb-4" v-else>
            <label class="block text-sm">Número</label>
            {{ form.address_number }}
        </div>

        <div class="mb-4" v-if="!disabled">
            <label class="block text-sm">Complemento </label>
            <input
                v-model="form.complement"
                type="text"
                class="mt-1 w-full border rounded px-2 py-1"
            />
        </div>
        <div class="mb-4" v-else>
            <label class="block text-sm">Complemento</label>
            {{ form.complement }}
        </div>


        <div class="mt-4">
            <button
                v-if="disabled"
                @click="disabled = !disabled"
                class="bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-50"
            >
                {{ 'Editar'  }}
            </button>

            <button
                v-else
                @click.prevent="submit"
                :disabled="form.processing"
                class="bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-50"
            >
                {{ 'Atualizar'  }}
            </button>
        </div>
    </form>
</template>


<script>
import axios from 'axios'
import bus from '@/eventBus';

export default {
    data: function () {
        return {
            disabled: true,
            form: {
                id: null,
                asaas_id: '',
                cpf_cnpj: '',
                name: '',
                email: '',
                phone: '',
                postal_code: '',
                address: '',
                address_number: '',
                complement: '',
                province: ''
            },
        }
    },
    methods: {
        async submit() {
            await axios.put(`api/asass/client/${this.form.id}`, this.form).then(({ data: { data } }) => {
                this.form = data
                this.disabled = true
                bus.emit('set-client', this.form);
            }).catch(error => {
                console.error('Error creating client:', error)
            })
        },
        setClient(client){
            this.canClient = !!client;
            this.form = client;
        },
    },
    created() {
        bus.on('set-client', (client) => {
            this.setClient(client);
        });
    },
}

</script>
