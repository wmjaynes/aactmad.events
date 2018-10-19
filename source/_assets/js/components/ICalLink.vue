<template>
        <a class="button is-link is-rounded is-outlined"
           target="_blank"
           title="Download iCal File"
           :href="icalLink">
            <span class="icon">
                <i class="far fa-calendar-alt"></i>
            </span>
            <span> iCal</span>
        </a>
</template>

<script>
    const ical = require('ical-generator');

    export default {
        name: "ical-link",
        props: ['event'],
        computed: {
            icalLink() {
                let cal = ical({
                    events: [
                        {
                            start: this.event.eventStartDate,
                            end: this.event.eventEndDate,
                            summary: this.event.EventName,
                            description: this.event.EventDesc,
                            location: this.addressStr(),
                        }
                    ]
                });
                let blob = new Blob([cal.toString()], {type:'text/calendar'});
                let file = window.URL.createObjectURL(blob);

                return file;
            },

        },
        methods: {
            addressStr() {
                let addressStr = this.event.EventStreet + ", " + this.event.EventCity + ", "
                    + this.event.EventState + " " + this.event.EventZip;
                return addressStr;
            },
        },
    }
</script>

<style scoped>

    .button {
        height: 1.25em;
    }

</style>
