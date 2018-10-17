<template>
    <header class="card-header event-header">

        <!--<div class='has-text-centered event-date-badge event-header-item is-hidden-tablet'>-->
            <!--<span class="value event-dayofweek text-nowrap">{{ event.EventDateDayofWeek }}</span>-->
            <!--<br/>-->
            <!--<span class="value event-monthday text-nowrap">{{ event.EventDateMonthDay }}</span>-->
        <!--</div>-->
        <!--<div class='has-text-centered event-date-box event-header-item is-hidden-mobile'>-->
            <!--<span class="value event-dayofweek text-nowrap">{{ event.EventDateDayofWeek }}</span>-->
            <!--<span class="value event-monthday text-nowrap">{{ event.EventDateMonthDay }}</span>-->
        <!--</div>-->

        <figure v-if="event.EventImageURL" class="image event-header-item ">
            <img class="event-image"
                 :src="event.EventImageURL">
        </figure>
        <figure v-else-if="isAactmadOrg(event)" class="image event-header-item ">
            <img class="event-image"
                 src="https://assets.aactmad.org/images/aactmad.logo.120x80.png">
        </figure>
        <div v-else class="event-header-item event-image ">
        </div>

        <div style="flex-grow: 2;" class=" event-header-item">
            <div class="event-name is-size-6">
                {{ event.EventName }}
            </div>
            <div class="is-size-6">
                <span class="value event-start-time text-nowrap"> {{event.eventStartDate.format('h:mm a')}} </span>
                -
                <span class="value event-end-time text-nowrap"> {{event.eventEndDateFormated}} </span>
            </div>
            <div class="is-size-6">
                <span class="value locality text-nowrap">{{ event.EventCity }}</span>
                <span class="value region text-nowrap">{{ event.EventState }}</span>
            </div>
        </div>

        <div class="event-header-icon  event-header-item is-pulled-right">
            <span
                    class="arrow"
                    :class="{ 'arrow--asc': this.event.displayBody, 'arrow--desc': !this.event.displayBody }"
            ></span>
        </div>
    </header>
</template>


<script>
    export default {
        name: "event-header",
        props: ['event'],

        methods: {
            aactmadOrgs() {
                return (["135", "824", "910", "830", "829", "812", "831",
                    "973", "1144", "828", "1058", "797", "1023", "1058", "2762"]);
            },
            isAactmadOrg(event) {
                return this.aactmadOrgs().includes(event.OrgID);
            },
        },
    }
</script>

<style scoped lang="scss">

    @import "../../sass/main.scss";

    .event-header {

        /*background-image: linear-gradient(180deg, #e7f4fd, #a0d5f8);*/

        cursor: pointer;
        align-items: center;
        margin: 0 -5px;
        line-height: 1rem;
        /*border: 1px solid #209cee;*/
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        /*height: 4rem;*/
    }

    img {
        width: 80px;
        height: auto;
    }
    .event-image {
        min-width: 80px;
        max-width: 80px;
        height: auto;
    }

    .event-date-badge {
        background: darken($info, 30%);
        border-radius: 100%;
        height: 4rem;
        width: 4rem;
        min-width: 4rem;
        padding: 8px;

        font-family: 'Open Sans', sans-serif;
        color: white;
        font-size: 0.80rem;
        line-height: 1rem;

        vertical-align: center;
        white-space: nowrap;
    }

    .event-date-box {
        background: darken($info, 30%);
        max-width: 6rem;
        min-width: 6rem;
        /*background: #0C2D5B;*/
        padding: 2px 8px;

        font-family: 'Open Sans', sans-serif;
        color: white;
        font-size: 0.9rem;
        line-height: 1.2rem;

        white-space: nowrap;

        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
    }


    .event-name {
        font-weight: bold;
    }

    .event-header-item {
        margin: 0 5px;
    }



    .arrow {
        display: inline-block;
        vertical-align: middle;
        width: 0;
        height: 0;
        margin-left: 5px;
        opacity: 0.66;

        &--asc {
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-bottom: 8px solid #222;
        }

        &--desc {
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-top: 8px solid #222;
        }
    }

</style>