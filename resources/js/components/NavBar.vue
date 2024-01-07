<template>
    <MDBNavbar
        container
        expand="lg"
        bg="light"
        class="d-flex justify-content-between"
    >
        <MDBNavbarBrand>
            <a :href="welcome" style="color: unset;"><span>Tab</span><span style="margin-left: -10px;">dmin</span></a>
        </MDBNavbarBrand>
        <MDBNavbarToggler
            target="#navbarColor01"
            @click="collapse7 = !collapse7"
        ></MDBNavbarToggler>
        <MDBCollapse id="navbarColor01" v-model="collapse7">
            <MDBNavbarNav class="mb-2 mb-lg-0">
                <MDBNavbarItem :href="home" v-if="!guest" :linkClass="url === home ? 'link-zeus' : ''"
                >Dashboard
                </MDBNavbarItem
                >
            </MDBNavbarNav>

            <MDBNavbarNav class="mb-2 mb-lg-0 d-flex justify-content-end" style="margin-right: unset !important;">
                <MDBNavbarItem v-if="guest" :href="login" class="me-3 me-lg-0">Login</MDBNavbarItem>
                <MDBNavbarItem v-else>
                    <MDBDropdown v-model="dropdown2">
                        <MDBDropdownToggle tag="a" class="nav-link" @click="dropdown2 = !dropdown2"
                        >{{ username }}
                        </MDBDropdownToggle
                        >
                        <MDBDropdownMenu>
                            <MDBDropdownItem href="#">Profile</MDBDropdownItem>
                            <MDBDropdownItem :href="logout"
                                             onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </MDBDropdownItem>
                            <form id="logout-form" :action="logout" method="POST" class="d-none">
                                <input type="hidden" name="_token" :value="csrf">
                            </form>
                        </MDBDropdownMenu>
                    </MDBDropdown>
                </MDBNavbarItem>
            </MDBNavbarNav>
        </MDBCollapse>
    </MDBNavbar>
</template>

<script>
import {
    MDBBadge,
    MDBCollapse,
    MDBDropdown,
    MDBDropdownItem,
    MDBDropdownMenu,
    MDBDropdownToggle,
    MDBIcon,
    MDBNavbar,
    MDBNavbarBrand,
    MDBNavbarItem,
    MDBNavbarNav,
    MDBNavbarToggler,
} from 'mdb-vue-ui-kit';

export default {
    name: "NavBar",
    components: {
        MDBNavbar,
        MDBNavbarItem,
        MDBNavbarBrand,
        MDBNavbarNav,
        MDBNavbarToggler,
        MDBBadge,
        MDBIcon,
        MDBCollapse,
        MDBDropdown,
        MDBDropdownToggle,
        MDBDropdownMenu,
        MDBDropdownItem,
    },
    props: [
        'guest',
        'login',
        'username',
        'logout',
        'csrf',
        'url',
        'welcome',
        'home',
    ],
    data() {
        return {
            collapse7: false,
            dropdown2: false,
        }
    }
}
</script>

<style scoped>

</style>
