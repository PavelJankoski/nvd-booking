<template>
    <div>
        <div class="invalid-feedback"
             v-for="(error, index) in errors" :key="key(index)">{{error}}</div>
    </div>
</template>

<script>
export default {
name: "ValidationError",
    props: ["errors"],
    methods: {
        key(index) {
            return `validation_error_${index}_${Math.random()}`;
        }
    }
}
</script>

<style scoped>

</style>
