<template>
    <div class="show_comment" :class="dClass" style="width: 99%;">
        <ul class="formComments" id="item_comment">
            <button v-if="this.$store.state.canComment && this.$store.state.user_can_comment" type="button" class="lgs_btn lgs_btn-primary lgs_btn-block col-md-12 pointer" @click="closeQuote" v-scroll-to="'#mainForm'">{{ t('leave_new_comment') }}</button>
            <div v-if="!this.$store.state.canComment" style="position: relative">
                <div style="text-align: center">
                    <label>{{t('you_can_vote')}}</label>
                    <p>{{t('you_should_login_first')}}</p>
                    <a :href="loginUrl" target="_blank" class="lgs_btn lgs_btn-primary">{{t('login_to_profile')}}</a>
                </div>
            </div>
            <div id="showResult" style="padding: 50px;" v-if="results.length>0">
                <div class="lgs_col-sm-6 lgs_float_left lgs_text_left">
                    <label><strong>{{t('avarage_present_item')}}</strong></label>
                    <div style="position: relative">
                        <label>{{t('for_base')}}<strong> {{this.count}} </strong> {{t('voted')}}</label>
                        <progress-bar size="huge" :val="all_avg" max="100" bar-color="rgb(121, 218, 249)" text-position="middle"></progress-bar><span class="span_bar">{{this.all_avg}}%</span>
                    </div>
                </div>
                <div class="lgs_col-sm-6 lgs_float_right show_left_result" style="border-right: 3px solid #eeeeee;">
                    <div v-for="res in results" :key="res.id" style="margin: 20px;position: relative">
                        <progress-bar size="big" :val="res.avg" max="100" :text="res.title" bar-color="rgb(121, 218, 249)" text-position="top"></progress-bar><span class="show_span_item_result">{{res.avg}}%</span>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <Item
                    class="item"
                    :model="treeData">
            </Item>
            <div  id="mainForm" v-if="this.$store.state.canComment && this.$store.state.user_can_comment">
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
                <commentForm :model="treeData" :items="items" :show_items=true></commentForm>
            </div>
        </ul>
    </div>
</template>
<script>
    import Item from './components/item.vue';
    import commentForm from './components/commentForm.vue';
    import Vuex from "vuex";
    import ProgressBar from 'vue-simple-progress'
    window.axios = require('axios');
    import VueTranslate from 'vue-translate-plugin'
    Vue.use(VueTranslate);
    import VueScrollTo from 'vue-scrollto';
    Vue.use(VueScrollTo, {
        container: "body",
        duration: 1000,
        easing: "ease-in-out",
        offset: -60,
        cancelable: true,
        onStart: false,
        onDone: false,
        onCancel: false,
        x: false,
        y: true
    })
    Vue.use(Vuex);
    const store = new Vuex.Store({
        state: {
            user_id:0,
            quote_id:0,
            canComment:true,
            user_can_comment:false,
            user_comment:false,
            data_array:[],
            model:[],
            num_child :0,
            rtl : true,
            items:[]
        },
    });
    export default  {
        name: 'laravel_comments_system',
        props: ['target_model_name', 'target_id', 'target_parent_column_name','direction'],
        data: function () {
            return {
                treeData: [],
                rtl:true,
                items:[],
                results:false,
                all_avg : false,
                count:0,
                loginUrl:false,
            }
        },
        computed: {
            dClass:function () {
                if(this.direction)
                {
                    return 'rtl' ;
                }
                else
                {
                    return 'ltr' ;
                }
            },
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
                    this.items = response.data.items ;
                    this.loginUrl = response.data.loginUrl ;
                    this.count = response.data.count ;
                    this.results = response.data.result ;
                    this.all_avg = response.data.all_avg ;
                    this.$store.state.user_can_comment = response.data.user_can_comment ;
                    this.$store.state.user_comment = response.data.user_comment ;
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
            Item,commentForm,ProgressBar
        },
        locales: {
            en:  require('./lang/en/comment.json'),
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
                "tanks_message" : "پیغام شما با موفقیت ثبت گردید",
                "avarage_present_item" : "میانگین رضایت مندی کاربران",
                "for_base" :"بر اساس",
                "voted" : 'رای داده شده',
                "you_can_vote" : "شما هم می‌توانید در مورد این کالا نظر بدهید.",
                "you_should_login_first" : "برای ثبت نظر، لازم است ابتدا وارد حساب کاربری خود شوید.",
                "login_to_profile":"ورود به حساب کاربری"
            }
        }
    }

</script>

<style scoped>
    @import  './lib/icon/style.css';
    @import  './assets/css/comment.css';
    .wrapper {
        height:150px;
        min-height:100px;
        max-height: 200px;
    }
</style>