<template>

    <ul class="formComments" id="item_comment">
        <button type="button" class="btn btn-primary col-md-12" @click="closeQuote" v-scroll-to="'#mainForm'">{{ t('leave_new_comment') }}</button>
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
        <div  id="mainForm" v-if="this.$store.state.canComment ">
            <div v-if="this.$store.state.quote_id != 0"  >
                <div class="comment_content main_quote">
                    <button type="button" class="close close_open_reply" style="position: absolute;right: 7px;z-index: 10;" @click="closeQuote">×</button>
                    <div class="comment_area_right quote_picture">
                        <img class="commenter_avatar" src="http://www.derayati.ir/FM/SRC/ID/-1/user_avatar.png">
                    </div>
                    <div class="comment_area_left">
                        <div class="comment_created_at" style="margin-right: 5%;">{{this.$store.state.data_array[this.$store.state.quote_id].created_at}}</div>
                        <h5>{{this.$store.state.data_array[this.$store.state.quote_id].name}}</h5>
                        <div>{{this.$store.state.data_array[this.$store.state.quote_id].comment}}</div>
                        <div class="clearfixed"></div>
                    </div>
                </div>
                <hr />
            </div>
            <commentForm :model="treeData"></commentForm>
        </div>

    </ul>

</template>
<script>
    import Item from './item.vue';
    import commentForm from './commentForm.vue';
    import VBar from '../../../../public/vendor/laravel_comments_system/packages/v-bar/src/components/v-bar.vue'

    export default {
        name:'laravel_comments_system',
        props: ['target_model_name', 'target_id', 'target_parent_column_name'],
        data: function () {
            return {
                treeData: [],
            }
        },
        mounted () {
            this.getData()
        },
        methods: {
            getData : function () {
                axios.post("/LCS/getData", {model: this.target_model_name, id: this.target_id, pid_key: this.target_parent_column_name}).then((response) => {
                    this.treeData = response.data;
                this.$store.state.user_id = response.data.user_id ;
                this.$store.state.canComment = response.data.canComment ;
                this.$store.state.data_array = response.data.data_array ;
                this.$store.state.data = response.data ;
                if (response.data.lang =='en')
                {
                    this.$translate.setLang("en");
                }
                else if (response.data.lang =='fa')
                {
                    this.$translate.setLang("fa");
                } else
                {
                    this.$translate.setLang("en");
                }
            });
            },
            closeQuote : function () {
                this.$store.state.quote_id = 0;
            },
        },
        components: {
            Item,commentForm,VBar,
        },
        locales: {
            en:  require('../../../../public/vendor/laravel_comments_system/lang/en/comment.json'),
            fa: {
                'leave_new_comment': 'کامنت جدید قرار دهید',
            }
        }
    }
</script>

<style scoped>
    @import  '../../../../public/vendor/laravel_comments_system/css/comment.css';
    .wrapper {
        height: 700px;
    }
</style>