<template>
        <a class="button is-link is-rounded is-outlined"
           target="_blank"
           title="Add to Google Calendar"
           :href="googleLink">
            <span class="icon">
                <i class="far fa-calendar-alt"></i>
            </span>
            <span> Google</span>
        </a>
</template>

<script>
    export default {
        name: "add-to-google-calendar-link",
        props: ['event'],
        computed: {
            googleLink() {
                // console.log(JSON.stringify(this.event));
                let url = 'https://www.google.com/calendar/event?action=TEMPLATE';
                url += '&text=' + encodeURIComponent(this.event.EventName);
                url += '&dates=' + this.event.eventStartDate.format('YYYYMMDDTHHmmss') + '/'
                    + this.event.eventEndDate.format('YYYYMMDDTHHmmss');
                url += '&details=' + encodeURIComponent(this.event.EventDesc);
                url += '&location=' + encodeURIComponent(this.addressStr());

                url += '&trp=false&sprop=&ctz=America%2FNew_York';


                return url;
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
