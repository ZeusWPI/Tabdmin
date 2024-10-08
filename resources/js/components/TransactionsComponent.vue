<template>
    <div class="d-flex justify-content-end mb-3">
        <MDBBtn color="zeus" @click="syncBankTransactions" class="">Sync bank transactions</MDBBtn>
    </div>
    <MDBCard class="mb-3">
        <MDBCardHeader>New transaction</MDBCardHeader>
        <MDBCardBody>
            <form @submit.prevent="submitTransaction">
                <MDBRow class="mb-3">
                    <MDBCol md="6">
                        <MDBInput type="number" step=".01" label="Amount" id="amount" v-model="form.amount" required/>
                    </MDBCol>
                    <MDBCol md="6">
                        <MDBCheckbox label="Cash" id="cash" v-model="form.cash"/>
                    </MDBCol>
                </MDBRow>
                <MDBRow class="mb-3">
                    <MDBCol md="6">
                        <MDBInput type="text" label="Debtor" id="debtor" v-model="form.debtor" required/>
                    </MDBCol>
                    <MDBCol md="6">
                        <MDBInput type="text" label="Creditor" id="creditor" v-model="form.creditor" required/>
                    </MDBCol>
                </MDBRow>
                <MDBRow class="mb-3">
                    <MDBCol md="12">
                        <MDBInput type="text" label="Message" id="message" v-model="form.message" required/>
                    </MDBCol>
                </MDBRow>
                <MDBBtn color="secondary" block type="submit" id="submitBtn">
                    Save
                </MDBBtn>
            </form>
        </MDBCardBody>
    </MDBCard>
    <MDBCard>
        <MDBCardHeader>Transactions</MDBCardHeader>
        <MDBCardBody>
            <MDBTable hover responsive>
                <thead>
                <tr>
                    <th>Time</th>
                    <th>Amount</th>
                    <th>Currency</th>
                    <th>Type</th>
                    <th>Debtor</th>
                    <th>Creditor</th>
                    <th>Issuer</th>
                    <th>Transaction ID</th>
                    <th>Executed</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(transaction, index) in transactions" :key="index">
                    <td>{{ formatDate(transaction.created_at) }}</td>
                    <td>{{ parseFloat(transaction.amount).toLocaleString('nl-BE', {minimumFractionDigits: 2}) }}</td>
                    <td>{{ transaction.currency }}</td>
                    <td>{{ transaction.cash ? 'Cash' : 'Bank' }}</td>
                    <td>{{ transaction.debtor }}</td>
                    <td>{{ transaction.creditor }}</td>
                    <td>{{ transaction.issuer }}</td>
                    <td>{{ transaction.transaction_id }}</td>
                    <td>{{ transaction.executed ? 'Yes' : 'No' }}</td>
                </tr>
                </tbody>
            </MDBTable>
        </MDBCardBody>
    </MDBCard>
</template>

<script>
import {
    MDBBtn,
    MDBCard,
    MDBCardBody,
    MDBCardHeader,
    MDBCardTitle,
    MDBCheckbox,
    MDBCol,
    MDBInput,
    MDBRow,
    MDBTable
} from 'mdb-vue-ui-kit';
import moment from "moment";

export default {
    name: "TransactionsComponent",
    components: {
        MDBCard,
        MDBCardBody,
        MDBCardTitle,
        MDBCardHeader,
        MDBTable,
        MDBRow,
        MDBCol,
        MDBInput,
        MDBCheckbox,
        MDBBtn,
    },
    props: [
        'transactionsProp',
    ],
    data() {
        return {
            transactions: this.transactionsProp,
            form: {
                amount: 0,
                debtor: 'zeus',
                creditor: '',
                cash: false,
                message: 'Tab opladen',
            }
        }
    },
    methods: {
        formatDate(date) {
            return moment(new Date(date)).format('DD/MM/YYYY HH:mm');
        },
        async submitTransaction() {
            // Disable the submit button to prevent double submissions
            document.getElementById('submitBtn').disabled = true;

            this.$toast.info('The transaction is being processed...');

            const headers = {'Content-Type': 'application/json', 'Accept': 'application/json'};
            const response = await axios.post('/transactions', JSON.stringify(this.form), {
                headers: headers,
                validateStatus: () => true
            });
            if (response.status === 201) {
                this.transactions.push(response.data.transaction);
                this.$toast.success('The transaction has been processed successfully.');

                // Reset the form
                this.form.amount = 0;
                this.form.debtor = 'zeus';
                this.form.creditor = '';
                this.form.cash = false;
                this.form.message = 'Tab opladen';
            } else {
                if (response?.data?.errors) {
                    for (const error of Object.values(response.data.errors)) {
                        for (let message of error) {
                            this.$toast.error(message);
                        }
                    }
                } else {
                    this.$toast.error('Something went wrong while processing the transaction.');
                }
            }

            // Enable the submit button
            document.getElementById('submitBtn').disabled = false;
        },
        async syncBankTransactions() {
            this.$toast.info('The bank transactions are being synchronized...');

            const response = await axios.post('/transactions/sync', {validateStatus: () => true});
            if (response.status === 200) {
                this.$toast.success('The bank transactions have been synchronized successfully.');
            } else {
                this.$toast.error('Something went wrong while synchronizing the bank transactions.');
            }
        }
    }
}
</script>

<style scoped>

</style>
