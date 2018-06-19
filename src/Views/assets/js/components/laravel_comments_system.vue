<template>

    <ul class="formComments" id="item_comment">
        <button type="button" class="btn btn-primary col-md-12" v-scroll-to="'#mainForm'">Leave New Comment</button>
        <v-bar wrapper="wrapper"
               vBar=""
               vBarInternal=""
               hBar=""
               hBarInternal="">
            <Item
                    class="item"
                    :model="treeData">
            </Item>
        </v-bar>
        <div v-if="this.$store.state.quote_id != 0"  >
            <div >{{this.$store.state.quote_id}}</div>
        </div>
        <commentForm ref="mainForm" :model="treeData" :hasquote="false"  id="mainForm"></commentForm>
    </ul>

</template>
<script>
    import Item from './item.vue';
    import commentForm from './commentForm.vue';
    import VBar from 'v-bar'

    export default {
        name:'laravel_comments_system',
        props: ['target_model_name', 'target_id', 'target_parent_column_name'],
        data: function () {
            return {
                treeData: [],
            }
        },
        computed : {
            quote () {
                if(this.$store.state.quote_id != 0)
                {
                    return array ;
                }
                else
                {
                    return '0' ;
                }
            },

        },
        mounted () {
            this.getdata();
        },
        methods: {
            getdata: function () {
                axios.post("/LCS/getData", {model: this.target_model_name, id: this.target_id, pid_key: this.target_parent_column_name}).then((response) => {
                    this.treeData = response.data;
                    this.$store.state.user_id = response.data.user_id ;
                    this.$store.state.data_array = response.data.data_array ;
                });
            }
        },
        components: {
            Item,commentForm,VBar
        },
    }
</script>

<style scoped>
    @import  '../../../../public/vendor/laravel_comments_system/css/comment.css';
    .wrapper {
        height: 700px;
    }
</style>