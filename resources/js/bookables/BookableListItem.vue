<template>
    <div class="card w-100">
        <router-link :to="{name: 'bookable', params: {id: id}}">
            <h5 class="card-header">
                {{title}}
            </h5>
        </router-link>
        <div class="card-body">
            <p class="card-text">
                {{description}}
            </p>
        </div>
        <div class="card-footer text-right text-muted">
            Price per night: ${{price}}
        </div>
    </div>

</template>

<script>
export default {
    name: "BookableListItem",
    props: {
        title: {
            type: String,
            required: true
        },
        description: {
            type: String,
            required: true
        },
        id: {
            type: Number,
            required: true
        },
        price: {
            required: true
        }
    }
}
</script>

<style scoped>

</style>
