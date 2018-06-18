<template>
    <div class="chatHistoryContainer">
        <div class="formComments"
             :class="{bold: isFolder}"
             @click="toggle"
             @dblclick="changeType">
            <div class="comment_theme">
                <div class="comment_area">
                    <div class="comment_area_right">
                        <img class="commenter_avatar" src="http://www.derayati.ir/FM/SRC/ID/-1/user_avatar.png">
                    </div>
                    <div class="comment_area_left">
                        <div class="comment_content">
                            <h5>
                                <a href="#">@{{ comments.name }}</a>
                            </h5>
                            <p>
                                @{{ comments.comment }}
                            </p>
                            <div class="clearfixed"></div>
                        </div>
                        <div class="clearfixed"></div>
                        <button @click="showForm" data-item_id="4" data-section_id="42" data-section_type="lesson" data-parent_id="4" class="btn btn-default btn-xs btn_reply_comment" type="button">
                            <i class="fa fa-reply"></i>
                            <span>پاسخ</span>
                        </button>
                        <div v-if="openForm" id="show_comment_form">
                            <!--@include('helpers.comment_reply_form')-->
                        </div>
                    </div>
                    <div v-if="isFolder">
                        <item
                                class="item"
                                v-for="(comments, index) in comments.children"
                                :key="index"
                                :model="comments">
                        </item>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfixed"></div>
    </div>
</template>
<script>
    var token = document.head.querySelector('meta[name="csrf-token"]');
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    export default {
        props: {
            comments: Object
        },
        data () {
            return {
                open: false,
                openForm:false
            }
        },
        created () {
          this.getdata();
        },
        computed: {
            isFolder: function () {
                return this.comments.children &&
                    this.comments.children.length
            }
        },
        methods: {
            toggle: function () {
                if (this.isFolder) {
                    this.open = !this.open
                }
            },
            showForm: function () {
                if (this.showForm) {
                    this.openForm = !this.openForm
                }
            },
            getdata:function (){
                axios.post("/LCS/Getdata", {model: 'App\\Article', id: '1',pid_key:'parent_id'})
                    .then((response)=>{
                    console.log(response.data);
                this.comments = response.data ;
            }) ;
            },
            changeType: function () {
                if (!this.isFolder) {
                    Vue.set(this.comments, 'children', [])
                    this.addChild()
                    this.open = true
                }
            }
        }
    }
</script>
