<script>
import axios from 'axios'

export default {
    data() {
        return {
            disabled: false,
            editing: false,
            form: {
                id: null,
                cpf_cnpj: '04152084081',
                name: 'Lucas',
                email: 'demo@exemplo.com',
                phone: '51981201903',
                postal_code: '93048390',
                address: 'Exemplo de Rua',
                address_number: '55',
                complement: '',
                province: 'Feitoria'
            },
        }
    },
    methods: {
        async submit() {
            if (!this.form.id) {
                await axios.post('api/asass/client', this.form).then(response => {
                    this.form = response.data.data
                    this.disabled = true
                }).catch(error => {
                    console.error('Error creating client:', error)
                })

            }

            await axios.put(`api/asass/client/${this.form.id}`, this.form).then(response => {
                this.form = response.data.data
                this.disabled = true
            }).catch(error => {
                console.error('Error creating client:', error)
            })
        }
    },
    mounted() {
        axios.get('api/asass/client')
            .then(response => {
                const data = response.data.data
                if (!data) {
                    this.disabled = false
                    return
                }

                this.form = data
                this.disabled = true
            })
            .catch(error => {
                console.error('Error fetching client data:', error)
            })
    }
}

</script>

<template>
    <h3 class="text-lg font-medium mb-4">

        <button
            v-if="form.id"
            @click.prevent="disabled = !disabled"
            class="bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-50 flex justify-end ml-auto"
        >
            Editar
        </button>
        {{ form.id ? 'Editar Cliente' : 'Novo Cliente' }}
    </h3>

    <form class="space-y-3">

        <div class="mb-4" v-if="!disabled">
            <label class="block text-sm">CPF / CNPJ</label>
            <input
                v-model="form.cpf_cnpj"
                type="text"
                class="mt-1 w-full border rounded px-2 py-1"
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


        <div class="mt-4" v-if="!disabled">
            <button
                @click.prevent="submit"
                :disabled="form.processing"
                class="bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-50"
            >
                {{ form.id ? 'Atualizar' : 'Cadastrar' }}
            </button>
        </div>
    </form>
</template>

