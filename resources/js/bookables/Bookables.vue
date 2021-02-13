<template>
    <div>
        <div class="row mb-4" v-for="row in rows" :key="'row' + row">
            <div class="col d-flex align-items-stretch" v-for="(bookable, column) in bookablesInRow(row)" :key="'row' + row + ' col' + column">
                <bookable-list-item :title="bookable.title" :description="bookable.description" :price="bookable.price" :id="bookable.id"></bookable-list-item>
            </div>
            <div class="col" v-for="p in placeholdersInRow(row)" :key="'placeholder' + p"></div>
        </div>
     </div>

</template>

<script>
import BookableListItem from './BookableListItem';

export default {
    name: "Bookables",
    components: {
        BookableListItem
    },
    data(){
        return{
            columns: 3,
            bookables: []
        }
    },
    computed: {
      rows(){
          return Math.ceil(this.bookables.length/this.columns);
      }
    },
    methods: {
        bookablesInRow(row) {
            return this.bookables.slice((row-1)*this.columns, row*this.columns);
        },
        placeholdersInRow(row){
            return this.columns - this.bookablesInRow(row).length;
        }
    },
    created() {
        axios.get("/api/bookables").then(res => {
            this.bookables = res.data.data;
        }).catch(err => {
            console.log(err);
        });
    },
    mounted() {
    },

}
</script>

<style scoped>

</style>
