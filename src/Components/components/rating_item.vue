<template>
    <div>
        <div class="review_slide_group">
            <div class="review_slide_item">
                <label class="auto_label lgs_float_left" style=" margin-top: 1.5rem;margin-bottom: 0">{{item.title}}</label>
                <div style="width: 316px;" class=" review_slide_percent selection">
                    <div class="review_slide_percent_item selection lgs_float_left" @click="changeActive(5,item.id)">
                        <i v-if="!is_active_5" class="lcs-icon fa-lcs-circle"></i>
                        <i v-else class="lcs-icon fa-lcs-dot-circle color_red"></i>
                        <input type="radio" name="item" value="5" class="auto_label">
                        <label class="radio_change"  v-if="is_active_text_5">5</label>
                    </div>
                    <div class="review_slide_percent_item selection lgs_float_left" @click="changeActive(4,item.id)">
                        <i v-if="!is_active_4" class="lcs-icon fa-lcs-circle"></i>
                        <i v-else class="lcs-icon fa-lcs-dot-circle color_red"></i>
                        <input type="radio" name="item" value="4" class="auto_label">
                        <label class="radio_change"  v-if="is_active_text_4">4</label>
                    </div>
                    <div class="review_slide_percent_item selection lgs_float_left" @click="changeActive(3,item.id)">
                        <i v-if="!is_active_3" class="lcs-icon fa-lcs-circle"></i>
                        <i v-else class="lcs-icon fa-lcs-dot-circle color_red"></i>
                        <input type="radio" name="item" value="3" class="auto_label">
                        <label class="radio_change"  v-if="is_active_text_3">3</label>
                    </div>
                    <div class="review_slide_percent_item selection lgs_float_left" @click="changeActive(2,item.id)">
                        <i v-if="!is_active_2" class="lcs-icon fa-lcs-circle"></i>
                        <i v-else class="lcs-icon fa-lcs-dot-circle color_red"></i>
                        <input type="radio" name="item" value="2" class="auto_label">
                        <label class="radio_change" v-if="is_active_text_2">2</label>
                    </div>
                    <div class="review_slide_percent_item  lgs_float_left"  @click="changeActive(1,item.id)" >
                        <i v-if="!is_active_1" class="lcs-icon fa-lcs-circle"></i>
                        <i v-else class="lcs-icon fa-lcs-dot-circle color_red"></i>
                        <input type="radio" name="item" value="1" class="auto_label">
                        <label class="radio_change" v-if="is_active_text_1">1</label>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "rating_item",
        props: ['item'],
        data: function () {
            return {
                is_active_1:false,
                is_active_2:false,
                is_active_3:false,
                is_active_4:false,
                is_active_5:false,
                is_active_text_1:false,
                is_active_text_2:false,
                is_active_text_3:false,
                is_active_text_4:false,
                is_active_text_5:false,
            }
        },
        computed : {
            data:function () {
                return this.$parent.data ;
            }
        },
        methods : {
            changeActive:function (e,i) {
                this.$store.state.items[i]=e ;
                if(e==1)
                {
                    this.is_active_1 = true;
                    this.is_active_2 = false;
                    this.is_active_3 = false;
                    this.is_active_4 = false;
                    this.is_active_5 = false;
                    this.is_active_text_1=true;
                    this.is_active_text_2=false;
                    this.is_active_text_3=false;
                    this.is_active_text_4=false;
                    this.is_active_text_5=false;

                }
                else if (e==2)
                {
                    this.is_active_1 = true;
                    this.is_active_2 = true;
                    this.is_active_3 = false;
                    this.is_active_4 = false;
                    this.is_active_5 = false;
                    this.is_active_text_1=false;
                    this.is_active_text_2=true;
                    this.is_active_text_3=false;
                    this.is_active_text_4=false;
                    this.is_active_text_5=false;



                }
                else if(e==3)
                {
                    this.is_active_1 = true;
                    this.is_active_2 = true;
                    this.is_active_3 = true;
                    this.is_active_4 = false;
                    this.is_active_5 = false;
                    this.is_active_text_1=false;
                    this.is_active_text_2=false;
                    this.is_active_text_3=true;
                    this.is_active_text_4=false;
                    this.is_active_text_5=false;



                }
                else if(e==4)
                {
                    this.is_active_1 = true;
                    this.is_active_2 = true;
                    this.is_active_3 = true;
                    this.is_active_4 = true
                    this.is_active_5 = false;
                    this.is_active_text_1=false;
                    this.is_active_text_2=false;
                    this.is_active_text_3=false;
                    this.is_active_text_4=true;
                    this.is_active_text_5=false;

                }
                else if(e==5)
                {
                    this.is_active_1 = true;
                    this.is_active_2 = true;
                    this.is_active_3 = true;
                    this.is_active_4 = true;
                    this.is_active_5 = true;
                    this.is_active_text_1=false;
                    this.is_active_text_2=false;
                    this.is_active_text_3=false;
                    this.is_active_text_4=false;
                    this.is_active_text_5=true;
                }

            },
        }
    }
</script>

<style scoped>
.color_red{
    color:red ;
}
</style>