<template>
    <ul class="formComments" id="item_comment">
        <Item
                class="item"
                :model="treeData">
        </Item>
        <commentForm class="commentForm" :model="treeData"></commentForm>
    </ul>
</template>
<script>
    import Item from './item.vue';
    import commentForm from './commentForm.vue';

    export default {
        name:'laravel_comments_system',
        props: ['target_model_name', 'target_id', 'target_parent_column_name'],
        data: function () {
            return {
                treeData: [],
            }
        },
        mounted () {
            this.getdata();
        },
        methods: {
            getdata: function () {
                axios.post("/LCS/getData", {model: this.target_model_name, id: this.target_id, pid_key: this.target_parent_column_name}).then((response) => {
                this.treeData = response.data;
            });
            }
        },
        components: {
            Item,commentForm
        },
    }
</script>

<style scoped>
    @import  '../../../../public/vendor/laravel_comments_system/css/comment.css';

</style>