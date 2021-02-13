<template>
    <div>
        <h6 class="text-uppercase text-secondary font-weight-bolder">
            Check Availability
            <transition name="fade">
                <span v-if="noAvailability" class="text-danger">(NOT AVAILABLE)</span>
                <span v-if="hasAvailability" class="text-success">(AVAILABLE)</span>
            </transition>
            </h6>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="from">From</label>
                <input type="text"
                       id="from"
                       name="from"
                       v-model="from"
                       class="form-control form-control-sm"
                       placeholder="Start date"
                       @keyup.enter="check"
                       :class="[{'is-invalid': this.errorFor('from')}]"/>
                <validation-error :errors="errorFor('from')"></validation-error>
            </div>
            <div class="form-group col-md-6">
                <label for="to">To</label>
                <input type="text"
                       id="to"
                       name="to"
                       v-model="to"
                       class="form-control form-control-sm"
                       placeholder="End date"
                       @keyup.enter="check"
                       :class="[{'is-invalid': this.errorFor('to')}]"/>
                <validation-error :errors="errorFor('to')"></validation-error>
            </div>
        </div>
        <button class="btn btn-secondary btn-block"
                @click="check"
                :disabled="loading">
                <span v-if="!loading">Check!</span>
                <span v-else><i class="fas fa-circle-notch fa-spin"></i> Checking...</span>
        </button>
    </div>
</template>

<script>
import {is422} from '../shared/utils/response';
import validationErrors from "../shared/mixins/validationErrors";
import {mapState} from "vuex";
export default {
    name: "Availability",
    mixins: [validationErrors],
    props: {
        bookableId: [String, Number]
    },
    data(){
        return{
            from: this.$store.state.lastSearch.from,
            to: this.$store.state.lastSearch.to,
            loading: false,
            status: null
        }
    },
    methods: {
        async check() {

                this.loading = true;
                this.errors = null;

                this.$store.dispatch('setLastSearch', {
                    from: this.from,
                    to: this.to
                });
                try {
                    this.status = (await axios.get(`/api/bookables/${this.bookableId}/availability?from=${this.from}&to=${this.to}`)).status;
                    this.$emit('availability', this.hasAvailability);
                } catch (err) {
                    if (is422(err)) {
                        this.errors = err.response.data.errors;
                    }
                    this.status = err.response.status;
                    this.$emit('availability', this.hasAvailability);
                }
                this.loading = false;

        }
    },
    computed: {
        ...mapState({
            isLoggedIn: 'isLoggedIn'
        }),
        hasErrors(){
            return 422 === this.status && this.errors !== null
        },
        hasAvailability(){
            return 200 === this.status;
        },
        noAvailability(){
            return 404 === this.status;
        }
    }
}
</script>

<style scoped>
    label{
        font-size: 0.7rem;
        text-transform: uppercase;
        color: gray;
        font-weight: bolder;
    }
    .is-invalid {
        border-color: #b22222;
    }
    .invalid-feedback {
        color: #b22222;
    }
</style>
