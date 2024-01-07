<template>
    <MDBCard class="mb-3">
        <MDBCardHeader>New transaction</MDBCardHeader>
        <MDBCardBody>
            <form @submit.prevent="submitTransaction">
                <MDBRow class="mb-3">
                    <MDBCol md="6">
                        <MDBInput type="number" label="Amount" id="amount" v-model="form.amount" required/>
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
                <MDBBtn color="secondary" block type="submit" id="submitBtn">
                    Opslaan
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
                debtor: '',
                creditor: 'zeus',
                cash: false,
            }
        }
    },
    methods: {
        formatDate(date) {
            return moment(new Date(date)).format('DD/MM/YYYY HH:mm');
        },
        async submitTransaction() {
            this.$toast.info('The transaction is being processed...');

            const headers = {'Content-Type': 'application/json', 'Accept': 'application/json'};
            const response = await axios.post('/transactions', JSON.stringify(this.form), {
                headers: headers,
                validateStatus: () => true
            });
            if (response.status === 201) {
                this.transactions.push(response.data.transaction);
                this.$toast.success('The transaction has been processed successfully.');
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
        },
    }
}
</script>

<style scoped>

</style>
