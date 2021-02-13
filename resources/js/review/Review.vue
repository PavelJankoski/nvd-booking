<template>
    <div>
        <div v-if="loading">
            <loading-component :size="100" :color="'#41b883'"></loading-component>
        </div>
        <div v-else>
            <div v-if="hasBooking">
                <fatal-error v-if="error"></fatal-error>
                <success v-if="success">
                    You've left a review, thank you very much!
                </success>
                <div class="row" v-if="!success && !error">
                    <div :class="[{'col-md-4' : twoColumns}, {'d-none': oneColumn}]">
                        <div class="card">
                            <div class="card-body">
                                <p>Stayed at
                                    <router-link :to="{name: 'bookable', params: {id: booking.bookable.bookable_id}}">
                                        {{ booking.bookable.title }}
                                    </router-link>
                                </p>
                                <p>From {{ booking.from }} to {{ booking.to }}</p>
                            </div>
                        </div>
                    </div>
                    <div :class="[{'col-md-8' : twoColumns}, {'col-md-12': oneColumn}]">

                        <div v-if="!alreadyReviewed">
                            <div class="form-group">
                                <label class="text-muted">Select the star rating (1 is worst - 5 is best)</label>
                                <star-rating class="fa-3x"
                                             v-model="review.rating"></star-rating>
                            </div>
                            <div class="form-group">
                                <label for="content" class="text-muted">Describe your experience with</label>
                                <textarea class="form-control"
                                          name="content"
                                          id="content"
                                          cols="30"
                                          rows="10"
                                          v-model="review.content"
                                          :class="[{'is-invalid': errorFor('content')}]"></textarea>
                                <validation-error :errors="errorFor('content')"></validation-error>

                            </div>
                            <button class="btn btn-lg btn-primary btn-block" @click.prevent="submit" :disabled="sending">
                                Submit
                            </button>
                        </div>
                        <div v-else>
                            <h3>You've already left a review for this booking!</h3>
                        </div>
                    </div>
                </div>
            </div>
            <fatal-error v-else></fatal-error>
        </div>

    </div>

</template>

<script>
import {is404, is422} from "../shared/utils/response";
import validationErrors from "../shared/mixins/validationErrors";
import {mapState} from "vuex";

export default {
    name: "Review",
    mixins: [validationErrors],
    data() {
        return {
            review: {
                id: null,
                rating: 5,
                content: null,
                user_id: null
            },
            existingReview: null,
            loading: true,
            booking: null,
            error: false,
            sending: false,
            success: false
        }
    },
    methods: {
        onRatingChanged(rating) {
            this.review.rating = rating;
        },
        submit() {
            this.errors = null;
            this.sending = true;
            this.review.user_id = this.user.id;
            axios.post('/api/reviews', this.review).then(res => {
                this.success = res.status === 201;
            }).catch(err => {
                const errors = err.response.data.errors;
                if (is422(err)) {
                    if (errors['content'] && _.size(errors) === 1) {
                        this.errors = errors;
                        return;
                    }
                }
                this.error = true;
            }).then(() => {
                this.sending = false;
            });
        }
    },
    async created() {
        this.review.id = this.$route.params.id;
        try {
            this.existingReview = (await axios.get(`/api/reviews/${this.review.id}`)).data.data;
        } catch (err) {
            if (is404(err)) {
                try {
                    await axios.get("/sanctum/csrf-cookie");
                    this.booking = (await axios.get(`/api/booking-by-review/${this.$route.params.id}`)).data.data;
                } catch (err2) {
                    this.error = !is404(err2);
                }
            } else {
                this.error = true;
            }
        }
        this.loading = false;

    },
    computed: {
        ...mapState({
            user: 'user'
        }),
        alreadyReviewed() {
            return this.hasReview || !this.booking;
        },
        hasReview() {
            return this.existingReview !== null;
        },
        hasBooking() {
            return this.booking !== null;
        },
        oneColumn() {
            return this.alreadyReviewed;
        },
        twoColumns() {
            return !this.alreadyReviewed;
        }
    }
}
</script>

<style scoped>

</style>
