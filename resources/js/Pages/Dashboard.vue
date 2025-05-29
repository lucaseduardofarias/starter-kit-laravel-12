
<template>
    <Head title="Pagamentos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Pagamentos ASAAS
            </h2>
        </template>

        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex gap-6">
                <!-- Coluna 1/3: formulário -->
                <div class="w-1/3 bg-white shadow p-4 rounded">
                    <ClientForm/>
                </div>

                <!-- Coluna 2/3: tabela, que vai buscar JSON -->
                <div class="flex-1 bg-white shadow p-4 rounded">
                    tabela de pagamentos
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

</template>

<script>

import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import ClientForm from '@/Components/ClientForm.vue'
import PaymentTable from '@/Components/PaymentTable.vue'


export default {
    components: { Head, AuthenticatedLayout, ClientForm, PaymentTable },

    props: {
        client: { type: Object, default: null }
    },

    methods: {
        reloadPayments() {
            // pede ao filho <PaymentTable> pra recarregar a lista
            this.$refs.paymentTable.fetchPayments()
        }
    }
}
</script>
