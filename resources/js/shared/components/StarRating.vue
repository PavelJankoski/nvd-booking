<template>
    <div id="stars" class="d-flex">
        <i class="fas fa-star" v-for="star in fullStars" :key="'full' + star" @click="$emit('input', star)"></i>
        <i class="fas fa-star-half-alt text-warning" v-if="halfStar"></i>
        <i class="far fa-star text-warning" v-for="star in emptyStars" :key="'empty' + star" @click="$emit('input', fullStars+star)"></i>

    </div>

</template>

<script>
export default {
    name: "StarRating",
    props: {
        //rating: Number
        value: Number
    },
    computed: {
        halfStar(){
            const fraction = Math.round(
                (this.value - Math.floor(this.value)) * 100
            );

            return fraction>0 && fraction<50;
        },
        fullStars(){
            return Math.round(this.value);
        },
        emptyStars(){
            return 5-Math.ceil(this.value);
        }
    }
}

</script>

<style scoped>
i {
    color: #f7c000;
}
</style>
