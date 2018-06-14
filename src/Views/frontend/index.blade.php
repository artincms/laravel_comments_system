@extends('laravel_comments_system::frontend.layouts.master')
@section('content')

@endsection
@section('content_javascript')
<!-- item template -->
<script type="text/x-template" id="item-template">
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
                                <a href="#">@{{ model.name }}</a>
                            </h5>
                            <p>
                                @{{ model.comment }}
                            </p>
                            <div class="clearfixed"></div>
                        </div>
                        <div class="clearfixed"></div>
                        <button  @click="showForm" data-item_id="4" data-section_id="42" data-section_type="lesson" data-parent_id="4" class="btn btn-default btn-xs btn_reply_comment" type="button">
                            <i class="fa fa-reply"></i>
                            <span>پاسخ</span>
                        </button>
                        <div v-if="openForm" id="show_comment_form">
                            @include('helpers.comment_reply_form')
                        </div>
                    </div>
                    <div  v-if="isFolder">
                        <item
                                class="item"
                                v-for="(model, index) in model.children"
                                :key="index"
                                :model="model">
                        </item>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfixed"></div>

    </div>
</script>
<!-- the demo root element -->
<ul id="demo">
    <item
            class="item"
            :model="treeData">
    </item>
</ul>
<script>

    var data = {!! $data !!} ;
    Vue.component('item', {
        template: '#item-template',
        props: {
            model: Object
        },
        data: function () {
            return {
                open: false,
                openForm:false
            }
        },
        computed: {
            isFolder: function () {
                return this.model.children &&
                    this.model.children.length
            }
        },
        methods: {
            toggle: function () {
                if (this.isFolder) {
                    this.open = !this.open
                }
            },
            showForm :function () {
                if (this.showForm) {
                    this.openForm = !this.openForm
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
            }
        }
    })

    // boot up the demo
    var demo = new Vue({
        el: '#demo',
        data: {
            treeData: data
        }
    })
</script>
@endsection