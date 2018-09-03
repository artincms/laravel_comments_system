<template>
    <div class="show_comment" :class="direction">
        <ul class="formComments" id="item_comment">
            <button type="button" class="lgs_btn lgs_btn-primary lgs_btn-block col-md-12" @click="closeQuote" v-scroll-to="'#mainForm'">{{ t('leave_new_comment') }}</button>
            <Item
                    class="item"
                    :model="treeData">
            </Item>
            <div  id="mainForm" v-if="this.$store.state.canComment ">
                <div v-if="this.$store.state.quote_id != 0"  >
                    <div class="main_quote comment_content">
                        <button type="button" class="close close_open_reply" style="position: absolute;right: 7px;z-index: 10;" @click="closeQuote">×</button>
                        <div class="quote_picture comment_area_right">
                            <img class="commenter_avatar" src='/LFM/DownloadFile/ID/0/small/user_avatar.png/100/272/208'>
                        </div>
                        <div class="comment_area_left">
                            <div class="comment_created_at" style="margin-right: 5%;">{{this.$store.state.data_array[this.$store.state.quote_id].created_at}}</div>
                            <h5  style="text-align: left;">{{this.$store.state.data_array[this.$store.state.quote_id].name}}</h5>
                            <div>{{this.$store.state.data_array[this.$store.state.quote_id].comment}}</div>
                            <div class="clearfixed"></div>
                        </div>
                    </div>
                    <hr />
                </div>
                <commentForm :model="treeData"></commentForm>
            </div>
        </ul>
    </div>
</template>
<script>
    import Item from './item.vue';
    import commentForm from './commentForm.vue';
    import VBar from './lib/v-bar'
    import axios from './lib/axios'
    import Vuex from "./lib/vuex/dist/vuex.js";
    Vue.use(Vuex);
    const store = new Vuex.Store({
        state: {
            user_id:0,
            quote_id:0,
            canComment:false,
            data_array:[],
            model:[],
            num_child :0,
            rtl : true
        },
    });
    export default  {
        name: 'laravel_comments_system',
        props: ['target_model_name', 'target_id', 'target_parent_column_name','direction'],
        data: function () {
            return {
                treeData: [],
                rtl:true
            }
        },
        store: store,
        mounted() {
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
                    this.num_child = response.data.children.length;
                    if (response.data.lang =='en')
                    {
                        this.$translate.setLang("en");
                        this.$store.state.rtl=false ;
                    }
                    else
                    {
                        this.$translate.setLang("fa");
                        this.$store.state.rtl=true ;
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
            en:  require('../../../../../public/vendor/laravel_comments_system/lang/en/comment.json'),
            fa: {
                'leave_new_comment': 'کامنت جدید قرار دهید',
                "send": "ارسال",
                "clear": "پاک کردن",
                "comment": "نظر",
                "reply": "پاسخ",
                "quote": "نقل قول",
                "you_commented_successfully": "پیام شما با موفقیت ثبت گردید .",
                "name_place_holder": "نام ..",
                "email_place_holder": "ایمیل ..",
                "write_your_message_here": "پیام خود را بنویسید ...",
                "tanks_message" : "پیغام شما با موفقیت ثبت گردید"
            }
        }
    }

</script>

<style scoped>
    @import  './assets/css/comment.css';
    .wrapper {
        height:150px;
        min-height:100px;
        max-height: 200px;
    }
</style>