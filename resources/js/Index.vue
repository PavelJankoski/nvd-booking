<template>
    <div>
        <nav class="navbar navbar-expand-lg bg-white border-bottom navbar-light">
            <router-link class="navbar-brand mr-auto" :to="{name: 'home'}">NVD-Booking</router-link>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <router-link class="nav-link" :to="{name: 'basket'}">
                        Basket
                        <span v-if="itemsInBasket" class="badge badge-secondary">{{itemsInBasket}}</span>
                    </router-link>
                </li>
                <li class="nav-item" v-if="!isLoggedIn">
                    <router-link :to="{name: 'register'}" class="nav-link">Register</router-link>
                </li>
                <li class="nav-item" v-if="!isLoggedIn">
                    <router-link :to="{name: 'login'}" class="nav-link">Sign-in</router-link>
                </li>
                <li class="nav-item" v-if="isLoggedIn">
                    <a href="#" class="nav-link" @click.prevent="logout">Log-out</a>
                </li>
            </ul>

         </nav>

        <div class="container mt-4 mb-4 pr-4 pl-4">
            <router-view></router-view>
        </div>

    </div>
</template>

<script>

import {mapState, mapGetters} from "vuex";
import {logOut} from './shared/utils/auth';
export default {
    name: "Index",
    computed: {
        ...mapState({
            isLoggedIn: 'isLoggedIn'
        }),
        ...mapGetters({
            itemsInBasket: "itemsInBasket"
        })
    },
    methods: {
        async logout() {
            try {
                await axios.post('/logout');
                await this.$store.dispatch('logOut');
            } catch(error) {
                await this.$store.dispatch('logOut');
            }
        }
    }

}
</script>

<style scoped>

</style>
