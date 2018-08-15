<template>
    <div id="commentForm">
        <div v-if="success.length"> <div class="alert alert-success">{{ success }}</div></div>
        <div v-if="errors.length">
            <div class="text-center"><h2>Please correct the following error(s):</h2></div>
            <ul>
                <div class="alert alert-danger" v-for="error in errors">{{ error }}</div>
            </ul>
        </div>
        <form id="form_reply_submit_comment" action=""  @submit.prevent="commentSubmit" novalidate="true">
            <input type="hidden" name="parent_id" :value="model.encode_id">
            <input class="section_id" type="hidden" name="target_id" :value="model.encode_target_id">
            <input class="section_type" type="hidden" name="target_type" :value="model.target_type">
            <input class="comment_type" type="hidden" name="comment_type" value="comment_reply">
            <div class="row" v-if=" this.$store.state.user_id == 0 " style="padding:10px">
                <div class="col-sm-6">
                    <div class="input-group input-group-sm chatMessageControls">
                        <input name="name" type="text" class="form-control clear_after_comment" :placeholder="t('name_place_holder')" aria-describedby="sizing-addon3" v-model="name">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="input-group input-group-sm chatMessageControls">
                        <input name="email" type="email" class="form-control clear_after_comment" :placeholder="t('email_place_holder')" aria-describedby="sizing-addon3" v-model="email">
                    </div>
                </div>
                <div class="clearfixed"></div>
            </div>
            <div class="row" style="padding: 10px;">
                <div class="col-xs-12 col-md-12">
                    <hr>
                    <div class="input-group input-group-sm chatMessageControls">
                        <span style="" class="input-group-addon comment_leave_message" id="sizing-addon3">نظر</span>
                        <textarea rows="3" name="comment" id="comment_text" type="text" class="form-control comment_text_area clear_after_comment" :placeholder="t('write_your_message_here')"
                                  aria-describedby="sizing-addon3" v-model="comment">
                        </textarea>
                    </div>
                </div>
            </div>
            <div class="row" style="margin:15px 0 10px">
                <div class="col-xs-12" style="padding-left: 10px;">
                    <div style="float:left;">
                        <button style="font-size: 12px;" name="commentReplySubmit" class="btn btn-primary commentReplySubmit" type="submit">{{ t('send') }}</button>
                        <button @click.prevent="clearForm" style="font-size: 12px; margin-left: 2px;" id="clearMessageButton" class="btn btn-default"type="button" value="reset">{{ t('clear') }}</button>
                    </div>
                </div>
            </div>
            <div class="clearfixed"></div>

        </form>
    </div>


</template>

<script>
    import axios from '../../../../../public/vendor/laravel_gallery_system/packages/axios/index.js'
    export default {
        name: "commentForm",
        props: ['model'],
        data: function () {
            return {
                errors: [],
                success:[],
                name:'',
                email:'',
                parent_id:'',
                section_id:'',
                section_type:'',
                comment : '',
            }
        },
        computed : {

        },
        methods: {
            updateValue: function (model) {
                this.$emit('input', model);
            },
            commentSubmit:function (e) {
                this.errors=[];
                this.success='';
                this.checkForm();
                if (!this.errors.length)
                {
                    axios.post("/LCS/saveComment", {
                        name: this.name,
                        email:this.email,
                        comment:this.comment,
                        parent_id:this.model.encode_id,
                        target_id:this.model.encode_target_id,
                        target_type:this.model.target_type,
                        quote_id: this.$store.state.quote_id
                    }).then((response) => {
                        if(response.data.success)
                    {
                        this.name = this.email =this.comment= '';
                        e.target.reset();
                        this.$store.state.quote_id = 0,
                        this.success ='پیغام شما با موفقیت ثبت گردید';
                    }
                });
                }

            },
            checkForm: function () {
                if ( this.$store.state.user_id == 0)
                {
                    if (!this.name) {
                        this.errors.push("Name required.");
                    }
                    if (!this.comment) {
                        this.errors.push("Comment required.");
                    }
                    if (!this.email) {
                        this.errors.push('Email required.');
                    } else if (!this.validEmail(this.email)) {
                        this.errors.push('Valid email required.');
                    }
                }
            },
            validEmail: function (email) {
                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            },
            clearForm : function (e) {
                this.name = this.email =this.comment= '';
            }

        },
    }
</script>

<style scoped>

</style>