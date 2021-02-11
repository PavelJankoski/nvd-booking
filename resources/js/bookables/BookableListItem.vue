<template>
    <div class="card w-100">
        <div class="card-body">
            <router-link :to="{name: 'bookable', params: {id: id}}">
                <h5 class="card-title">
                    {{title}}
                </h5>
            </router-link>

            <p class="card-text">
                {{description}}
            </p>
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
        }
    }
}
</script>

<style scoped>

</style>
