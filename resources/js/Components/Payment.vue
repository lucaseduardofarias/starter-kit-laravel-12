<template>
    <div v-if="canClient" class="bg-white shadow-md rounded-lg p-6 space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between border-b pb-4">
            <h1 class="text-2xl font-bold">Pagamentos</h1>
            <button
                type="button"
                @click="createPayment"
                class="bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2 rounded"
            >
                Realizar Pagamento
            </button>
        </div>

        <!-- Table of Payments -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Status</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Produto</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Valor</th>
                    <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Tipo</th>
                    <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Realizado</th>
                    <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Atualizado</th>
                    <th class="px-4 py-2"></th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="payment in payments" :key="payment?.id" class="hover:bg-gray-50">
                    <td :class="`px-4 py-2 text-center bg-status-${payment.status}`">
                        {{ formatStatus(payment.status) }}
                    </td>
                    <td class="px-4 py-2">{{ payment.description }}</td>
                    <td class="px-4 py-2">{{ formatValue(payment.value) }}</td>
                    <td class="px-4 py-2 text-center">{{ formatType(payment.billing_type) }}</td>
                    <td class="px-4 py-2 text-center">{{ formatDate(payment.created_at) }}</td>
                    <td class="px-4 py-2 text-center">{{ formatDate(payment.updated_at) }}</td>
                    <td class="px-4 py-2 text-right">
                        <button
                            @click="showPayment(payment.id, payment.billing_type)"
                            type="button"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1 rounded pb-px"
                        >
                            Visualizar
                        </button>
                        <button
                            @click="updateStatusPayment(payment.id)"
                            type="button"
                            class="bg-green-500 hover:bg-green-600 focus:ring-2 focus:ring-green-300 text-white text-sm px-3 py-1 rounded"
                        >
                            Atualizar
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal: Realizar Pagamento -->
        <div
            v-if="showModalPayment"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl max-h-[90vh] overflow-auto">
                <div class="bg-green-600 px-4 py-3 rounded-t-lg">
                    <h5 class="text-lg font-semibold text-white">REALIZAR PAGAMENTO</h5>
                </div>
                <form @submit.prevent="submitPayment" class="px-6 py-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Left Side -->
                        <div>
                            <!-- Produto -->
                            <div>
                                <label for="value" class="block mb-1 font-medium">Qual produto deseja contratar?</label>
                                <select
                                    id="value"
                                    v-model="paymentValue"
                                    class="block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring"
                                >
                                    <option v-for="row in values" :key="row.value" :value="row.value">{{
                                            row.name
                                        }}
                                    </option>
                                </select>
                            </div>
                            <!-- Tipo -->
                            <div>
                                <label for="type" class="block mb-1 font-medium mt-3">Qual a forma de pagamento?</label>
                                <select
                                    id="type"
                                    v-model="paymentType"
                                    class="block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring"
                                >
                                    <option v-for="row in types" :key="row.value" :value="row.value">{{
                                            row.name
                                        }}
                                    </option>
                                </select>
                            </div>

                            <!-- Credit Card Section -->
                            <div v-if="paymentType === PAYMENT_TYPES.CREDIT_CARD">
                                <!-- Cartões existentes -->
                                <div v-if="cards.length" class="mt-4">
                                    <label for="card" class="block mb-1 font-medium">Selecione um cartão de
                                        crédito:</label>
                                    <select
                                        id="card"
                                        v-model="paymentCard"
                                        class="block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring"
                                    >
                                        <option v-for="row in cards" :key="row.id" :value="row.id">{{
                                                row.name
                                            }}
                                        </option>
                                    </select>
                                </div>
                                <!-- Parcelas -->
                                <div v-if="paymentValue" class="mt-4">
                                    <p class="text-green-600 font-semibold">Parcelas</p>
                                    <select
                                        id="installment"
                                        v-model="paymentInstallment"
                                        class="block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring mt-1"
                                    >
                                        <option v-for="row in installments" :key="row.value" :value="row.value">
                                            {{ row.name }}
                                        </option>
                                    </select>
                                </div>
                                <!-- Dados do Cartão -->
                                <div v-if="!paymentCard" class="mt-4 space-y-4">
                                    <p class="text-green-600 font-semibold">Informe os dados do cartão</p>
                                    <div>
                                        <label for="number" class="block mb-1 font-medium">Número do cartão:</label>
                                        <input
                                            id="number"
                                            v-model="creditCard.number"
                                            type="text"
                                            class="block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring"
                                        />
                                    </div>
                                    <div>
                                        <label for="holderName" class="block mb-1 font-medium">Nome no cartão:</label>
                                        <input
                                            id="holderName"
                                            v-model="creditCard.holder_name"
                                            type="text"
                                            class="block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring"
                                        />
                                    </div>
                                    <div class="grid grid-cols-3 gap-3">
                                        <div>
                                            <label for="expiryMonth" class="block mb-1 font-medium">Mês Validade</label>
                                            <input
                                                id="expiryMonth"
                                                v-model="creditCard.expiry_month"
                                                type="number"
                                                class="block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring"
                                            />
                                        </div>
                                        <div>
                                            <label for="expiryYear" class="block mb-1 font-medium">Ano Validade</label>
                                            <input
                                                id="expiryYear"
                                                v-model="creditCard.expiry_year"
                                                type="number"
                                                class="block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring"
                                            />
                                        </div>
                                        <div>
                                            <label for="ccv" class="block mb-1 font-medium">CVV</label>
                                            <input
                                                id="ccv"
                                                v-model="creditCard.ccv"
                                                type="number"
                                                class="block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring"
                                            />
                                        </div>
                                    </div>
                                    <div>
                                        <label for="isHolderSelect" class="block mb-1 font-medium">Você é
                                            titular?</label>
                                        <select
                                            id="isHolderSelect"
                                            v-model="isHolder"
                                            class="block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring"
                                        >
                                            <option value="yes">SIM</option>
                                            <option value="no">NÃO</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Holder Info -->
                        <div v-if="isHolder === 'no'">
                            <p class="text-green-600 font-semibold mb-3">Dados do titular do cartão</p>
                            <div class="space-y-4">
                                <div>
                                    <label for="holderCpfCnpj" class="block mb-1 font-medium">CPF / CNPJ</label>
                                    <input
                                        id="holderCpfCnpj"
                                        v-model="holder.cpf_cnpj"
                                        type="text"
                                        class="block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring"
                                    />
                                </div>
                                <div>
                                    <label for="holderNameInput" class="block mb-1 font-medium">Nome</label>
                                    <input
                                        id="holderNameInput"
                                        v-model="holder.name"
                                        type="text"
                                        class="block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring"
                                    />
                                </div>
                                <div>
                                    <label for="holderEmail" class="block mb-1 font-medium">E-mail</label>
                                    <input
                                        id="holderEmail"
                                        v-model="holder.email"
                                        type="email"
                                        placeholder="email@exemplo.com"
                                        class="block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring"
                                    />
                                </div>
                                <div>
                                    <label for="holderPhone" class="block mb-1 font-medium">Celular</label>
                                    <input
                                        id="holderPhone"
                                        v-model="holder.phone"
                                        type="text"
                                        placeholder="11911119999"
                                        class="block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring"
                                    />
                                </div>
                                <!-- Endereço -->
                                <p class="text-green-600 font-semibold mt-6 mb-3">Endereço</p>
                                <div class="space-y-4">
                                    <div>
                                        <label for="holderPostalCode" class="block mb-1 font-medium">CEP</label>
                                        <input
                                            id="holderPostalCode"
                                            v-model="holder.postal_code"
                                            type="text"
                                            placeholder="11111999"
                                            class="block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring"
                                        />
                                    </div>
                                    <div>
                                        <label for="holderAddress" class="block mb-1 font-medium">Endereço</label>
                                        <input
                                            id="holderAddress"
                                            v-model="holder.address"
                                            type="text"
                                            class="block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring"
                                        />
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="holderAddressNumber"
                                                   class="block mb-1 font-medium">Número</label>
                                            <input
                                                id="holderAddressNumber"
                                                v-model="holder.address_number"
                                                type="text"
                                                class="block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring"
                                            />
                                        </div>
                                        <div>
                                            <label for="holderComplement"
                                                   class="block mb-1 font-medium">Complemento</label>
                                            <input
                                                id="holderComplement"
                                                v-model="holder.complement"
                                                type="text"
                                                class="block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- Modal Footer -->
                <div class="flex justify-end border-t px-6 py-4 space-x-4">
                    <button
                        type="button"
                        @click="showModalPayment = false"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded"
                    >Cancelar
                    </button>
                    <button
                        v-if="!loading"
                        @click="submitPayment"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded"
                    >
                        {{
                            paymentType === PAYMENT_TYPES.PIX ? 'Gerar QRCode' : paymentType === PAYMENT_TYPES.BOLETO ? 'Emitir Boleto' : 'Finalizar Pagamento'
                        }}
                    </button>
                    <div v-else class="px-4 py-2 bg-gray-500 rounded text-white">Processando...</div>
                </div>
            </div>
        </div>

        <div
            v-if="showModalPix"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-auto overflow-auto max-h-[90vh]">
                <div class="bg-black px-4 py-3 rounded-t-lg">
                    <h5 class="text-lg font-semibold text-white">PIX - FINALIZE O PAGAMENTO</h5>
                </div>
                <div class="px-6 py-4 text-center space-y-4">
                    <p>Acesse seu APP de pagamentos e leia o QR Code abaixo para efetuar o pagamento de forma rápida e segura.</p>
                    <img :src="`data:image/jpeg;base64,${modal.pix.encoded_image}`" alt="PIX QR Code" class="mx-auto" />
                    <p>Ou copie e cole o código abaixo:</p>
                    <pre class="bg-gray-100 text-sm p-2 rounded break-words whitespace-pre-wrap">
                        {{ modal.pix.payload }}
                    </pre>
                </div>
                <div class="flex justify-end border-t px-6 py-4 space-x-2">
                    <button @click="showModalPix = false" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Fechar</button>
                    <button @click="copyText" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Copiar código</button>
                </div>
            </div>
        </div>

        <!-- Modal: Boleto -->
        <div
            v-if="showModalBoleto"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl overflow-auto max-h-[90vh]">
                <div class="bg-black px-4 py-3 rounded-t-lg">
                    <h5 class="text-lg font-semibold text-white">BOLETO - FINALIZE O PAGAMENTO</h5>
                </div>
                <div class="text-center p-0">
                    <iframe :src="modal.boleto.bank_slip_url" width="100%" height="450px"></iframe>
                </div>
                <div class="flex justify-end border-t px-6 py-4 space-x-2">
                    <button @click="showModalBoleto = false" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Fechar</button>
                    <a :href="modal.boleto.bank_slip_url" target="_blank" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Acessar link do boleto</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Cartão de Crédito -->
    <div
        v-if="showModalCreditCard"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
        <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl overflow-auto max-h-[90vh]">
            <div class="bg-black px-4 py-3 rounded-t-lg">
                <h5 class="text-lg font-semibold text-white">CARTÃO DE CRÉDITO - PARCELAS</h5>
            </div>
            <div class="px-6 py-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Descrição</th>
                        <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Valor</th>
                        <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Vencimento</th>
                        <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Cartão Utilizado</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="item in modal.credit_card" :key="item.id" class="hover:bg-gray-50">
                        <td :class="`px-4 py-2 text-center bg-status-${item.status}`">
                            {{ formatStatus(item.status) }}
                        </td>
                        <td class="px-4 py-2">{{ (!item.installmentNumber ? 'À Vista. ' : '') + item.description }}</td>
                        <td class="px-4 py-2 text-center">{{ formatValue(item.value) }}</td>
                        <td class="px-4 py-2 text-center">{{ formatDate(item.dueDate) }}</td>
                        <td class="px-4 py-2 text-center">{{ formatCard(item.creditCard.creditCardNumber, item.creditCard.creditCardBrand) }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end border-t px-6 py-4">
                <button @click="showModalCreditCard = false" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Fechar</button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import bus from '@/eventBus';
import debounce from 'lodash/debounce';
import { useToast } from 'vue-toastification';
const toast = useToast();
export default {
    name: 'PaymentsComponent',
    setup() {
        return {
            PAYMENT_TYPES: {
                PIX: 'PIX',
                BOLETO:'BOLETO',
                CREDIT_CARD: 'CREDIT_CARD'
            }
        }
    },
    data() {
        return {
            canClient: false,
            client: null,
            payments: [],
            showModalPayment: false,
            showModalPix: false,
            showModalBoleto: false,
            showModalCreditCard: false,
            paymentValue: '',
            paymentType: '',
            paymentCard: '',
            paymentInstallment: '',
            isHolder: 'yes',
            values: [],
            types: [],
            cards: [],
            installments: [],
            holder: {
                cpf_cnpj: '',
                name: '',
                email: '',
                phone: '',
                postal_code: '',
                address: '',
                province: '',
                address_number: '',
                complement: ''
            },
            creditCard: {
                number: '',
                holder_name: '',
                expiry_month: '',
                expiry_year: '',
                ccv: ''
            },
            loading: false,
            modal: {
                pix: {
                    encodedImage: '',
                    payload: ''
                },
                boleto: '',
                credit_card: []
            }
        };
    },
    created() {
        bus.on('set-client', this.setClient);
    },
    watch: {
        paymentValue: debounce(function () {
            this.fetchInstallments();
        }, 300),
        paymentType: debounce(function () {
            this.resetPaymentFields();
        }, 300),
        paymentCard: debounce(function () {
            this.resetPaymentFields();
        }, 300),
        isHolder: debounce(function () {
            this.resetHolderFields();
        }, 300)
    },
    methods: {
        getDueDate() {
            const d = new Date();
            d.setDate(d.getDate() + 5);
            return d.toISOString().split('T')[0];
        },
        buildPayload() {
            const due_date = this.getDueDate();
            const description = this.getValueDescription(this.paymentValue)
            const base = {
                customer: this.paymentType === this.PAYMENT_TYPES.PIX ? null : this.client.asaas_id,
                billing_type: this.paymentType,
                value: parseFloat(this.paymentValue),
                due_date,
                description
            };

            if (this.paymentType !== this.PAYMENT_TYPES.CREDIT_CARD) {
                return base;
            }

            if (this.isHolder === 'no') {
                base.credit_card_holder_info = {
                    name: this.holder.name,
                    email: this.holder.email,
                    cpf_cnpj: this.holder.cpf_cnpj,
                    postal_code: this.holder.postal_code,
                    address_number: this.holder.address_number,
                    address_complement: this.holder.complement,
                    phone: this.holder.phone
                };
            }

            // CREDIT_CARD
            return {
                ...base,
                days_after_due_date_to_registration_cancellation: 1,
                external_reference: this.client.id,
                installment_count: this.paymentInstallment || null,
                installment_value: null,
                credit_card: {
                    holder_name: this.creditCard.holder_name,
                    number: this.creditCard.number,
                    expiry_month: this.creditCard.expiry_month,
                    expiry_year: this.creditCard.expiry_year,
                    ccv: this.creditCard.ccv
                },
            };
        },
        async submitPayment() {
            this.loading = true;
            const payload = this.buildPayload();
            try {
                const { data } = await axios.post(`/api/asass/client/${this.client.id}/payments`, payload);
                toast.success('Pagamento registrado com sucesso!');
                this.showModalPayment = false;
                await this.showPayment(data.data.id, data.data.billing_type);
                await this.fetchPayments();
                this.loading = false;
            } catch (error) {
                this.loading = false;
                const resp = error.response;
                if (resp.status === 422) alert(`Erros no formulário:\n${resp.data.data}`);
                else alert(resp.data.data);
            }
        },
        async showPayment(id, type) {
            try {
                const { data } = await axios.get(`/api/asass/client/${this.client.id}/payment/${id}`);
                if (type === this.PAYMENT_TYPES.PIX) {
                    this.modal.pix = data.data;
                    this.showModalPix = true;
                } else if (type === this.PAYMENT_TYPES.BOLETO) {
                    this.modal.boleto = data.data;
                    this.showModalBoleto = true;
                } else if (type === this.PAYMENT_TYPES.CREDIT_CARD) {
                    this.modal.credit_card = data.data;
                    this.showModalCreditCard = true;
                }
            } catch (error) {
                this.handleApiError(error);
            }
        },
        async updateStatusPayment(id) {
            try {
                const { data } = await axios.get(`/api/asass/client/${this.client.id}/payment/${id}/status`);

                const payment = this.payments.find(p => p.id === id);
                if (payment) {
                    payment.status = data.data.status;
                }
            } catch (error) {
                this.handleApiError(error);
            }
        },
        setClient(client) {
            this.client = client;
            this.canClient = Boolean(client);
            if (this.canClient) this.fetchPayments();
        },
        createPayment() {
            if (!this.client.name || !this.client.cpf_cnpj || !this.client.email || !this.client.phone ||
                !this.client.postal_code || !this.client.address || !this.client.address_number) {
                alert('Preencha todos os dados do cliente antes de criar um pagamento.');
                return;
            }

            this.fetchValues();
            this.fetchTypes();
            this.fetchCards();
            this.paymentValue = '';
            this.paymentType = '';
            this.paymentCard = '';
            this.paymentInstallment = '';
            this.isHolder = 'yes';
            this.holder = this.getDefaultData('holder');
            this.creditCard = this.getDefaultData('creditCard');
            this.installments = [];
            this.showModalPayment = true;
        },
        resetPaymentFields() {
            this.paymentInstallment = '';
            this.creditCard = this.getDefaultData('creditCard');
        },
        resetHolderFields() {
            this.holder = this.getDefaultData('holder');
        },
        getDefaultData(key) {
            const defaults = {
                holder: {
                    cpf_cnpj: '',
                    name: '',
                    email: '',
                    phone: '',
                    postal_code: '',
                    address: '',
                    province: '',
                    address_number: '',
                    complement: ''
                },
                creditCard: {
                    number: '',
                    holder_name: '',
                    expiry_month: '',
                    expiry_year: '',
                    ccv: ''
                }
            };
            return JSON.parse(JSON.stringify(defaults[key]));
        },
        async fetchPayments() {
            const { data } = await axios.get(`/api/asass/client/${this.client.id}/payments`);
            this.payments = data.data;
        },
        fetchValues() {
            this.values = [
                { value: '', name: 'Selecione' },
                { value: '1200.00', name: 'R$ 1.200,00 - INTAKE N55' },
                { value: '1500.00', name: 'R$ 1.500,00 - INTAKE N55 + MAPEAMENTO' }
            ];
        },
        fetchTypes() {
            this.types = [
                { value: '', name: 'Selecione'},
                { value: this.PAYMENT_TYPES.PIX, name: 'PIX' },
                { value: this.PAYMENT_TYPES.BOLETO, name: 'BOLETO' },
                { value: this.PAYMENT_TYPES.CREDIT_CARD, name: 'CARTÃO'}
            ];
        },
        async fetchCards() {
            const { data } = await axios.get(`/api/asass/client/${this.client.id}/cards`);
            this.cards = data.data;
        },
        fetchInstallments() {
            const list = [];
            const v = parseFloat(this.paymentValue);
            if (!isNaN(v)) {
                list.push({value: '', name: `À Vista ${this.formatValue(v)}`});
                for (let i = 2; i <= 12; i++) {
                    list.push({value: i, name: `${i} x ${this.formatValue(v / i)}`});
                }
            }
            this.installments = list;
        },
        getValueDescription(val) {
            const f = this.values.find(v => v.value === val);
            return f ? f.name : '';
        },
        formatStatus(s) {
            const m = {PENDING: 'PENDENTE', RECEIVED: 'CONFIRMADO'};
            return m[s] || s;
        },
        formatType(t) {
            return t === this.PAYMENT_TYPES.CREDIT_CARD ? 'CARTÃO' : t;
        },
        formatValue(v) {
            return parseFloat(v).toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
        },
        formatDate(d) {
            return new Date(d).toLocaleDateString('pt-BR');
        },
        formatInstallment(value, type, inst) {
            if (type !== this.PAYMENT_TYPES.CREDIT_CARD) return '---';

            return inst ? `${inst}x ${this.formatValue(value / inst)}` : 'À Vista';
        },
        copyText() {
            navigator.clipboard.writeText(this.modal.pix.payload);
            toast.success('Código PIX copiado para a área de transferência!');
        },
        handleApiError(error) {
            const resp = error.response;
            if (resp && resp.data) {
                const { errors, message } = resp.data;
                let msg = message[0] || '';

                if (errors && typeof errors === 'object') {
                    const details = Object.values(errors)
                        .flat()
                        .filter(Boolean)
                        .join('\n');
                    if (details) {
                        msg += '\n\n' + details;
                    }
                }

                toast.error(msg || 'Erro ao processar a solicitação.');
            } else {
                toast.error('Erro desconhecido. Verifique sua conexão e tente novamente.');
            }
        },
    }
};
</script>

<style scoped>
.bg-status-PENDING{
    background: #ff9a00;
}
.bg-status-RECEIVED{
    background: #22c55e;
}
</style>
