<template>
    <MDBCard class="mb-3">
        <MDBCardHeader>New IBAN-Username pair</MDBCardHeader>
        <MDBCardBody>
            <form @submit.prevent="submitIbanUsername">
                <MDBRow class="mb-3">
                    <MDBCol md="6">
                        <MDBInput type="text" label="IBAN" id="iban" v-model="form.iban" required/>
                    </MDBCol>
                    <MDBCol md="6">
                        <MDBInput type="text" label="Username" id="username" v-model="form.username" required/>
                    </MDBCol>
                </MDBRow>
                <MDBBtn color="secondary" block type="submit" id="submitBtn">
                    Save
                </MDBBtn>
            </form>
        </MDBCardBody>
    </MDBCard>
    <MDBCard>
        <MDBCardHeader>IBAN-Username pairs</MDBCardHeader>
        <MDBCardBody>
            <MDBTable hover responsive>
                <thead>
                <tr>
                    <th>IBAN</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(ibanUsername, index) in ibanUsernames" :key="index">
                    <td>{{ ibanUsername.iban }}</td>
                    <td>{{ ibanUsername.username }}</td>
                    <td>
                        <MDBBtn color="danger" size="sm" rounded @click="deleteIbanUsername(ibanUsername)">
                            <MDBIcon icon="trash"/>
                        </MDBBtn>
                    </td>
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
    MDBIcon,
    MDBInput,
    MDBRow,
    MDBTable
} from 'mdb-vue-ui-kit';
import Swal from "sweetalert2";

export default {
    name: "IbanUsernamesComponent",
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
        MDBIcon,
    },
    props: [
        'ibanUsernamesProp',
    ],
    data() {
        return {
            ibanUsernames: this.ibanUsernamesProp,
            form: {
                iban: '',
                username: '',
            }
        }
    },
    methods: {
        async submitIbanUsername() {
            // Disable the submit button to prevent double submissions
            document.getElementById('submitBtn').disabled = true;

            this.$toast.info('The IBAN-Username pair is being registered...');

            const headers = {'Content-Type': 'application/json', 'Accept': 'application/json'};
            const response = await axios.post('/iban-usernames', JSON.stringify(this.form), {
                headers: headers,
                validateStatus: () => true
            });
            if (response.status === 201) {
                this.ibanUsernames.push(response.data.iban_username);
                this.$toast.success('The IBAN-Username pair has been registered successfully.');

                // Reset the form
                this.form.iban = '';
                this.form.username = '';
            } else {
                if (response?.data?.errors) {
                    for (const error of Object.values(response.data.errors)) {
                        for (let message of error) {
                            this.$toast.error(message);
                        }
                    }
                } else {
                    this.$toast.error('Something went wrong while registering the IBAN-Username pair.');
                }
            }

            // Enable the submit button
            document.getElementById('submitBtn').disabled = false;
        },
        async deleteIbanUsername(ibanUsername) {
            const result = await Swal.fire({
                title: 'Are you sure you want to delete this IBAN-Username pair?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel',
            });
            if (result.isConfirmed) {
                const response = await axios.delete(`/iban-usernames/${ibanUsername.id}`, {validateStatus: () => true});
                if (response.status === 200) {
                    this.ibanUsernames = this.ibanUsernames.filter(pair => pair.id !== ibanUsername.id);
                    this.$toast.success('The IBAN-Username pair has been successfully deleted.');
                    return true;
                } else {
                    this.$toast.error('Something went wrong while deleting the IBAN-Username pair.');
                    return false;
                }
            }
        },
    }
}
</script>

<style scoped>

</style>
