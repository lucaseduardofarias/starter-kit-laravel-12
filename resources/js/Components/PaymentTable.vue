<template>
    <h3 class="text-lg font-medium mb-4">
        {{ form.id ? 'Editar Cliente' : 'Novo Cliente' }}
    </h3>
    <form @submit.prevent="submit" class="space-y-3">
        <!-- repita para cada campo da tabela client -->
        <div v-for="f in fields" :key="f.key">
            <label class="block text-sm">{{ f.label }}</label>
            <input
                v-model="form[f.key]"
                :disabled="processing"
                class="mt-1 w-full border rounded px-2 py-1"
            />
            <p v-if="errors[f.key]" class="text-red-600 text-xs">
                {{ errors[f.key][0] }}
            </p>
        </div>
        <button
            type="submit"
            :disabled="processing"
            class="bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-50"
        >
            {{ form.id ? 'Atualizar' : 'Cadastrar' }}
        </button>
    </form>
</template>

<script>
import { Inertia } from '@inertiajs/inertia'

export default {
    props: {
        initialClient: { type: Object, default: () => ({}) }
    },
    data() {
        return {
            form: {
                id:             this.initialClient.id || null,
                cpf_cnpj:       this.initialClient.cpf_cnpj || '',
                name:           this.initialClient.name || '',
                email:          this.initialClient.email || '',
                phone:          this.initialClient.phone || '',
                postal_code:    this.initialClient.postal_code || '',
                address:        this.initialClient.address || '',
                address_number: this.initialClient.address_number || '',
                complement:     this.initialClient.complement || '',
                province:       this.initialClient.province || '',
            },
            errors:     {},
            processing: false,
            fields: [
                { key: 'cpf_cnpj',       label: 'CPF / CNPJ' },
                { key: 'name',           label: 'Nome' },
                { key: 'email',          label: 'E-mail' },
                { key: 'phone',          label: 'Telefone' },
                { key: 'postal_code',    label: 'CEP' },
                { key: 'address',        label: 'Endereço' },
                { key: 'address_number', label: 'Número' },
                { key: 'complement',     label: 'Complemento' },
                { key: 'province',       label: 'Bairro' },
            ],
        }
    },
    watch: {
        initialClient: {
            handler(c) {
                if (!c) return
                this.form = {
                    id:             c.id,
                    cpf_cnpj:       c.cpf_cnpj,
                    name:           c.name,
                    email:          c.email,
                    phone:          c.phone,
                    postal_code:    c.postal_code,
                    address:        c.address,
                    address_number: c.address_number,
                    complement:     c.complement,
                    province:       c.province,
                }
            },
            immediate: true,
        }
    },
    methods: {
        submit() {
            this.processing = true
            this.errors     = {}
            const payload = { ...this.form }

            const call = this.form.id
                ? Inertia.put(route('client.update', { id: this.form.id }), payload)
                : Inertia.post(route('client.store'), payload)

            call.then(() => {
                this.processing = false
                this.$emit('saved')
            }).catch(err => {
                this.processing = false
                if (err.response?.status === 422) {
                    this.errors = err.response.data.errors
                }
            })
        }
    }
}
</script>
