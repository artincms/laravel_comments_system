<template>
    <div v-if="main">
        <Item
                class="item"
                v-for="(model, index) in model.children"
                :key="index"
                :model="model">
        </Item>
    </div>
    <div class="comment_theme" :id="'item_comment_'+model.encode_id" v-else>
        <div class="comment_area">
            <div class="comment_area_right">
                <img class="commenter_avatar" :src="link">
            </div>
            <div class="comment_area_left">
                <div class="comment_content">
                    <div v-if="model.quote_id >0 ">
                        <div class="main_quote comment_content">
                            <div class="quote_picture" >
                                <img class="commenter_avatar" :src="link">
                            </div>
                            <div class="comment_area_left">
                                <div class="comment_created_at">{{this.$store.state.data_array[model.encode_quote_id].created_at}}</div>
                                <h5 style="text-align: left">{{model.name}}</h5>
                                <div>{{this.$store.state.data_array[model.encode_quote_id].comment}}</div>
                                <div class="clearfixed"></div>
                            </div>
                        </div>
                        <hr />
                    </div>
                    <div style="padding-top: 1%;">
                        <div class="comment_created_at">{{model.created_at}}</div>
                        <h5 style="text-align: left"><a href="#">{{model.name}}</a></h5>
                        <p  style="min-height: 80px;">{{model.comment}}</p>
                        <div class="clearfixed"></div>
                    </div>
                </div>
                <div class="clearfixed"></div>
                <div class="comment_btn_area" v-if="user_can_comment(model.encode_id)">
                    <button @click="showForm" :data-item_id='model.id' :data-target_id="model.target_id" :data-target_type="model.target_type" :data-parent_id='model.encode_parent_id' class="lgs_btn lgs_btn-default btn-sm btn_reply_comment" type="button">
                        <i class="lcs-icon fa-lcs-reply"></i>
                        <span>{{t('reply')}}</span>
                    </button>
                    <button v-scroll-to="'#mainForm'" @click="setQuote" :data-id="model.encode_id"  ref="quoteButton"   class="lgs_btn lgs_btn-default btn-sm btn_quote_comment" type="button">
                        <i class="lcs-icon fa-lcs-quote-right" style="font-size: 12px;"></i>
                        <span>{{t('quote')}}</span>
                    </button>
                </div>
                <div class="clearfixed"></div>
            </div>
            <div class="clearfixed"></div>
        </div>
        <div  v-if="openForm && canComment" id="show_comment_form'" class="form_comment_reply" >
            <div class="row" style="margin: 0">
                <button type="button" class="close close_open_reply"  @click="showForm">×</button></div>
            <commentForm v-if="canComment" class="commentForm" :model="model" items="" :show_items=false></commentForm>
            <div id="formScroll"></div>
        </div>
        <div class="clearfixed"></div>
        <div class="comment_children"  v-show="open" v-if="isFolder">
            <Item
                    class="item"
                    v-for="(model, index) in model.children"
                    :key="index"
                    :model="model">
            </Item>
        </div>
        <div class="clearfixed"></div>

    </div>
</template>

<script>
    import Item from './item.vue';
    import commentForm from './commentForm.vue';
    export default {
        name: "Item",
        props: ['model'],
        data: function () {
            return {
                openForm:false,
            }
        },
        computed: {

            link:function () {
              return 'http://www.derayati.ir/FM/SRC/ID/-1/user_avatar.png';
            },
            isFolder: function () {
                return this.model.children &&
                    this.model.children.length
            },
            canComment:function () {
              return  this.$store.state.canComment
            },
            open:function () {
                if (this.model.children &&this.model.children.length)
                {
                    return true ;
                }
                return false ;
            },
            main:function () {
                if (this.model.id == 0)
                {
                    return true ;
                }
                return false ;
            },
            user_comment:function () {
                return this.$store.state.user_comment ;
            }
        },
        methods: {
            setQuote: function (e) {
                this.$store.state.quote_id = e.currentTarget.dataset.id ;
            },
            toggle: function () {
                if (this.isFolder) {
                    this.open = !this.open
                }
            },
            changeType: function () {
                if (!this.isFolder) {
                    Vue.set(this.model, 'children', [])
                    this.addChild()
                    this.open = true
                }
            },
            addChild: function () {
                this.model.children.push({
                    name: 'new stuff'
                })
            },
            showForm: function () {
                this.$store.state.quote_id = 0;
                if (this.showForm) {
                    this.openForm = !this.openForm
                }
            },
            user_can_comment :function (id) {
               if(!this.$store.state.user_can_comment)
               {
                   if(this.user_comment.length > 0 && this.canComment)
                   {
                       if (this.user_comment.indexOf(id) ==-1)
                       {
                           return true ;
                       }
                       else
                       {
                           return false ;
                       }
                   }
                   else
                   {
                       return false ;
                   }
               }
               else
               {
                   return true ;
               }
            }
        },
        components: {
            Item,commentForm,
        },
    }
</script>

<style scoped>

</style>