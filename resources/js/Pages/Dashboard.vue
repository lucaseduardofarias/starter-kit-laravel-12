
<template>
    <Head title="Pagamentos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                <HeaderVue title="Sistema de pagamentos exemplo"/>
            </h2>
        </template>

        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex gap-6">
                <div class="w-1/3 bg-white shadow p-4 rounded">
                    <ClientForm/>
                </div>

                <div class="flex-1 bg-white shadow p-4 rounded">
                    <Payment/>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

</template>

<script>

import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import ClientForm from '@/Components/ClientForm.vue'
import Payment from '@/Components/Payment.vue'
import HeaderVue from '@/Components/Header.vue'


export default {
    components: {HeaderVue, Head, AuthenticatedLayout, ClientForm, Payment },

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
