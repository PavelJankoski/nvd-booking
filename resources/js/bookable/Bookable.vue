<template>
    <div v-if="!loading" class="row">
        <div class="col-md-8 pb-4">
            <div class="card">
                <div class="card-body">
                    <h2>{{bookable.title}}</h2>
                    <hr/>
                    <article>{{bookable.description}}</article>
                </div>
            </div>
            <review-list :bookable-id="this.$route.params.id"></review-list>
        </div>
        <div class="col-md-4 pb-4">
            <availability :bookable-id="this.$route.params.id"
                          v-on:availability="checkPrice($event)"
                          class="mb-4"></availability>
            <transition name="fade">
                <price-breakdown class="mb-4"
                                 :price="price"
                                 v-if="price"></price-breakdown>
            </transition>
            <transition name="fade">
                <button class="btn btn-outline-secondary btn-block"
                        v-if="price"
                        :disabled="inBasketAlready"
                        @click="addToBasket">Book now</button>
            </transition>
            <button class="btn btn-outline-secondary btn-block"
                    v-if="inBasketAlready"
                    @click="removeFromBasket">Remove from basket</button>
            <div v-if="inBasketAlready" class="mt-4 text-muted warning">
                Seems like you've added this object to the basket already. If you want to change dates, remove from the basket first
            </div>
        </div>
    </div>
    <div v-else>
        <loading-component :size="100" :color="'#41b883'">

        </loading-component>
    </div>
</template>

<script>
import Availability from "./Availability";
import ReviewList from "./ReviewList";
import PriceBreakdown from "./PriceBreakdown";
import {mapState} from 'vuex';
export default {
    name: "Bookable",
    components: {
        Availability,
        ReviewList,
        PriceBreakdown
    },
    data(){
        return{
            bookable: null,
            loading: true,
            price: null
        }
    },
    methods: {
        async checkPrice(hasAvailability) {
            if(!hasAvailability) {
                this.price = null;
                return
            }
            try {
                this.price  = (await axios.get(`/api/bookables/${this.bookable.id}/price?from=${this.lastSearch.from}&to=${this.lastSearch.to}`)).data.data;
            } catch (err) {
                this.price = null;
            }
        },
        addToBasket() {
            this.$store.dispatch("addToBasket", {
                bookable: this.bookable,
                price: this.price,
                dates: this.lastSearch
            })
        },
        removeFromBasket() {
            this.$store.dispatch('removeFromBasket', this.bookable.id);
        }
    },
    created() {
        const bookableId = this.$route.params.id;
        axios.get('/api/bookables/' + bookableId).then(res => {
            this.bookable = res.data.data;
            this.loading = false;
        }).catch(err => {
            console.log(err);
            this.loading = false;
        });
    },
    computed: {
        ...mapState({
            lastSearch: 'lastSearch'
        }),
        inBasketAlready() {
            if (null === this.bookable) {
                return false;
            }
            return this.$store.getters.inBasketAlready(this.bookable.id);
        }
    },

}
</script>

<style scoped>
    .semipolar-spinner{
        margin: 0 auto 0 auto;
    }
    .warning {
        font-size: 0.7rem;
    }
</style>
