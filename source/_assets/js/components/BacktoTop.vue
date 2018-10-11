<template>
    <button class="goTop" v-if="isVisible" @click="backToTop">
        Go To Top
        <i class="fas fa-arrow-circle-up"></i>
    </button>
</template>

<script>

    export default {
        name: "backtotop",
        data() {
            return {
                isVisible: false
            };
        },
        methods: {
            initToTopButton: function () {
                $(document).bind('scroll', function () {
                    var backToTopButton = $('.goTop');
                    if ($(document).scrollTop() > 250) {
                        backToTopButton.addClass('isVisible');
                        this.isVisible = true;
                    } else {
                        backToTopButton.removeClass('isVisible');
                        this.isVisible = false;
                    }
                }.bind(this));
            },
            backToTop: function () {
                $('html,body').stop().animate({
                    scrollTop: 0
                }, 'slow', 'swing');
            }
        },
        mounted: function () {
            this.$nextTick(function () {
                this.initToTopButton();
            });
        }
    }


</script>


<style scoped>
    .goTop {
        position: fixed;
        bottom: 2em;
        right: 2em;
        text-decoration: none;
        color: #FFF;
        background-color: rgba(0,0,0,0.3);
        font-size: 12px;
        padding: 0.5em;
        z-index: 9999;
    }
    /*.goTop {*/
        /*border-radius: 5px;*/
        /*background-color: rgb(1, 14, 27);*/
        /*background-color: rgba(1, 14, 27, .7);*/
        /*position: fixed;*/
        /*width: 45px;*/
        /*height: 45px;*/
        /*display: block;*/
        /*right: 15px;*/
        /*bottom: 15px;*/
        /*border: none;*/
        /*opacity: 0;*/
        /*z-index: -1;*/
    /*}*/
    .fas {
        color: white;
        font-size: 22px;
    }

    .isVisible {
        opacity: .8;
        z-index: 4;
        transition: all .4s ease-in;
    }
</style>