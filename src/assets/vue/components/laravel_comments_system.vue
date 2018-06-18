<template>
    <ul id="item_comment">
        <Item
                class="item"
                :model="treeData">
        </Item>
    </ul>
</template>
<script>
    import Item from './item.vue';

    export default {
        props: ['model_name', 'id', 'parrent_id_key'],
        data: function () {
            return {
                treeData: Object,
            }
        },
        mounted () {
            this.getdata();
        },
        methods: {
            getdata: function () {
                axios.post("/LCS/Getdata", {model: this.model_name, id: this.id, pid_key: this.parrent_id_key}).then((response) => {
                    console.log(response.data);
                this.treeData = response.data;
            });
            }
        },
        components: {
            Item,
        },
    }
</script>

<style scoped>

</style>