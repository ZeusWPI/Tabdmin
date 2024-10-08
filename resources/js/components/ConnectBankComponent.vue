<template>
    <MDBCard class="mb-3">
        <MDBCardHeader>Connected Accounts</MDBCardHeader>
        <MDBCardBody>
            <MDBCardText>
                <p v-if="accounts.length === 0">There are no bank accounts connected yet.</p>
                <MDBTable class="align-middle mb-0 bg-white" sm v-else>
                    <thead class="bg-light">
                    <tr>
                        <th>Bank</th>
                        <th>IBAN</th>
                        <th>Connection date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(account, index) of accounts" :key="index">
                        <td>{{ account.institution_id }}</td>
                        <td>{{ account.iban }}</td>
                        <td>{{ formatDate(account.created) }}</td>
                        <td>{{ account.status }}</td>
                        <td>
                            <MDBBtn color="danger" size="sm" rounded @click="deleteAccount(account)">
                                <MDBIcon icon="trash"/>
                            </MDBBtn>
                        </td>
                    </tr>
                    </tbody>
                </MDBTable>
            </MDBCardText>
        </MDBCardBody>
    </MDBCard>

    <MDBCard>
        <MDBCardHeader>Connect Bank Account</MDBCardHeader>
        <MDBCardBody>
            <MDBCardText style="margin-top: 1.5em;">
                <MDBRow class="mb-4">
                    <MDBCol col="12 col-xxl-4 col-lg-6" v-for="(bank, index) in banks" :key="index">
                        <MDBCard class="mb-2">
                            <MDBCardBody>
                                <div class="d-flex align-items-center">
                                    <img :src="bank.logo" alt="bank logo" style="height: 55px; width: auto;"/>
                                    <div class="ms-3">
                                        <p class="fw-bold mb-1">{{ bank.name }}</p>
                                        <MDBBtn color="secondary" size="sm" @click="connectBank(bank.id)">Connect
                                        </MDBBtn>
                                    </div>
                                </div>
                            </MDBCardBody>
                        </MDBCard>
                    </MDBCol>
                </MDBRow>
            </MDBCardText>
        </MDBCardBody>
    </MDBCard>
</template>

<script>
import {
    MDBBtn,
    MDBCard,
    MDBCardBody,
    MDBCardHeader,
    MDBCardImg,
    MDBCardText,
    MDBCardTitle,
    MDBCol,
    MDBIcon,
    MDBRow,
    MDBTable,
} from "mdb-vue-ui-kit";
import moment from "moment";
import Swal from "sweetalert2";

export default {
    name: "ConnectBankComponent",
    computed: {
        moment() {
            return moment
        }
    },
    components: {
        MDBCardHeader,
        MDBTable, MDBCol, MDBRow, MDBCardTitle, MDBCardText, MDBCard, MDBCardBody, MDBCardImg, MDBBtn, MDBIcon
    },
    props: [
        'banks',
        'accountsProp',
    ],
    data() {
        return {
            accounts: this.accountsProp,
        }
    },
    methods: {
        connectBank(bankId) {
            window.location.href = '/bankAccounts/connect/' + bankId;
        },
        formatDate(date) {
            return moment(new Date(date)).format('DD/MM/YYYY HH:mm');
        },
        async deleteAccount(account) {
            const result = await Swal.fire({
                title: 'Weet je zeker dat je dit bank account wilt verwijderen?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ja, verwijder',
                cancelButtonText: 'Annuleer',
            });
            if (result.isConfirmed) {
                const response = await axios.delete(`/bankAccounts/${account.id}`, {validateStatus: () => true});
                if (response.status === 200) {
                    this.accounts = this.accounts.filter(acc => acc.id !== account.id);
                    this.$toast.success('Het bank account is succesvol verwijderd.');
                    return true;
                } else {
                    this.$toast.error('Er ging iets fout tijdens het verwijderen van het bank account.');
                    return false;
                }
            }
        },
    }
}
</script>


<style scoped>

</style>
