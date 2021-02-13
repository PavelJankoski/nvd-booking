<template>
    <div>
        <success v-if="success">
            Congratulations on your purchase!
        </success>
        <div class="row" v-else>
            <div class="col-md-8" v-if="itemsInBasket">
                <div class="form-row">
                    <div class="col-md-6 form-group">
                        <label for="firstName">First name</label>
                        <input type="text"
                               name="firstName"
                               id="firstName"
                               class="form-control"
                               v-model="customer.first_name"
                               :class="[{'is-invalid': this.errorFor('customer.first_name')}]">
                        <validation-error :errors="errorFor('customer.first_name')"></validation-error>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="lastName">Last name</label>
                        <input type="text"
                               name="lastName"
                               id="lastName"
                               class="form-control"
                               v-model="customer.last_name"
                               :class="[{'is-invalid': errorFor('customer.last_name')}]">
                        <validation-error :errors="errorFor('customer.last_name')"></validation-error>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12 form-group">
                        <label for="email">Email</label>
                        <input type="text"
                               name="email"
                               id="email"
                               class="form-control"
                               v-model="customer.email"
                               :class="[{'is-invalid': errorFor('customer.email')}]">
                        <validation-error :errors="errorFor('customer.email')"></validation-error>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6 form-group">
                        <label for="street">Street</label>
                        <input type="text"
                               name="street"
                               id="street"
                               class="form-control"
                               v-model="customer.street"
                               :class="[{'is-invalid': errorFor('customer.street')}]">
                        <validation-error :errors="errorFor('customer.street')"></validation-error>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="city">City</label>
                        <input type="text"
                               name="city"
                               id="city"
                               class="form-control"
                               v-model="customer.city"
                               :class="[{'is-invalid': errorFor('customer.city')}]">
                        <validation-error :errors="errorFor('customer.city')"></validation-error>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6 form-group">
                        <label for="country">Country</label>
                        <input type="text"
                               name="country"
                               id="country"
                               class="form-control"
                               v-model="customer.country"
                               :class="[{'is-invalid': errorFor('customer.country')}]">
                        <validation-error :errors="errorFor('customer.country')"></validation-error>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="state">State</label>
                        <input type="text"
                               name="state"
                               id="state"
                               class="form-control"
                               v-model="customer.state"
                               :class="[{'is-invalid': errorFor('customer.state')}]">
                        <validation-error :errors="errorFor('customer.state')"></validation-error>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="zip">Zip</label>
                        <input type="text"
                               name="zip"
                               id="zip"
                               class="form-control"
                               v-model="customer.zip"
                               :class="[{'is-invalid': errorFor('customer.zip')}]">
                        <validation-error :errors="errorFor('customer.zip')"></validation-error>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-md-12 form-group">
                        <button type="submit"
                                class="btn btn-lg btn-primary btn-block"
                                :disabled="loading"
                                @click.prevent="book">Book now!</button>
                    </div>
                </div>
            </div>
            <div class="col-md-8" v-else>
                <div class="jumbotron jumbotron-fluid text-center">
                    <h1>Empty</h1>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex justify-content-between">
                    <h6 class="text-uppercase text-secondary font-weight-bolder">Your cart</h6>
                    <h6 class="badge badge-secondary text-uppercase">
                        <span v-if="itemsInBasket">Items {{itemsInBasket}}</span>
                        <span v-else>Empty</span>
                    </h6>
                </div>
                <transition-group name="fade" tag="div">
                    <div v-for="item in basket" :key="item.bookable.id">
                        <div class="py-2 border-top d-flex justify-content-between">
                        <span>
                            <router-link :to="{name: 'bookable', params: {id:item.bookable.id}}">{{item.bookable.title}}</router-link>
                        </span>
                            <span class="font-weight-bold">
                            ${{item.price.total}}
                        </span>
                        </div>
                        <div class="py-2 d-flex justify-content-between">
                        <span>
                            From {{item.dates.from}}
                        </span>
                            <span>
                            To {{item.dates.to}}
                        </span>
                        </div>
                        <div class="py-2 text-right">
                            <button class="btn btn-sm btn-outline-secondary" @click="$store.dispatch('removeFromBasket', item.bookable.id)">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </transition-group>

            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapState} from 'vuex';
import validationErrors from "../shared/mixins/validationErrors";
export default {
    name: "Basket",
    mixins: [validationErrors],
    data() {
        return {
            loading: false,
            bookingAttempted: false,
            customer: {
                first_name: null,
                last_name: null,
                email: null,
                street: null,
                city: null,
                country: null,
                state: null,
                zip: null
            }
        }
    },
    computed: {
        ...mapGetters(["itemsInBasket"]),
        ...mapState({
            basket: state => state.basket.items,
            user: state => state.user
        }),
        success() {
            return !this.loading && this.itemsInBasket === 0 && this.bookingAttempted
        }
    },
    methods: {
        async book() {
            this.loading = true;
            this.bookingAttempted = false;
            this.errors = null;
            try {
                await axios.post(`/api/checkout`, {
                    customer: this.customer,
                    bookings: this.basket.map(basketItem => ({
                        bookable_id: basketItem.bookable.id,
                        from: basketItem.dates.from,
                        to: basketItem.dates.to,
                        user_id: this.user.id
                    }))
                });
                await this.$store.dispatch('clearBasket')
            } catch (error) {
               this.errors = error.response.data.errors;
            }
            this.loading = false;
            this.bookingAttempted = true;
        }
    }
}
</script>

<style scoped>
    h6.badge {
        font-size: 100%;
    }
    a{
        color: black;
    }
</style>
