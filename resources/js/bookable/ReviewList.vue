<template>
    <div style="padding: 1.25rem;">
        <h6 class="text-uppercase text-secondary font-weight-bolder pt-4">Review list</h6>
        <div v-if="!loading">
            <div class="border-bottom d-none d-md-block" v-for="(review, idx) in reviews" :key="idx">
                <div class="row pt-4">
                    <div class="col-md-6">
                        Pavel Jankoski
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                            <star-rating :value="review.rating" class="fa-lg"></star-rating>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {{review.created_at | fromNow}}
                    </div>
                </div>
                <div class="row py-4">
                    <div class="col-md-12">
                        {{review.content}}
                    </div>
                </div>
            </div>

        </div>
        <loading-component v-else :size="50" :color="'#41b883'"></loading-component>

    </div>

</template>

<script>
export default {
    name: "ReviewList",
    props: {
        bookableId: [String, Number]
    },
    data() {
        return {
            loading: true,
            reviews: []
        }
    },
    created() {

        axios.get(`/api/bookables/${this.bookableId}/reviews`).then(res => {
            this.reviews = res.data.data;
        }).catch(err => {

        }).then(() => {
            this.loading = false;
        })
    }
    //,
    // filters: {
    //     fromNow(value){
    //         return moment(value).fromNow()
    //     }
    // }
}
</script>

<style scoped>

</style>
