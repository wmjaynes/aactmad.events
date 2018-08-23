<template>
    <p>
        <strong>Location:</strong>
        <a target="_blank" :href="mapUrl">{{ event.EventVenueName }} - {{ addressStr }} </a>
    </p>
</template>

<script>
    export default {
        name: "location-map-link",
        props: ['event'],
        computed: {
            mapUrl() {
                return 'https://maps.google.com/maps?q=' + this.event.EventStreet + ", "
                    + this.event.EventCity + ", " + this.event.EventState;
            },
            addressStr() {
                let addressStr = this.event.EventStreet + ", " + this.event.EventCity + ", "
                    + this.event.EventState + " " + this.event.EventZip;
                return addressStr;
            },
        },
    }
</script>

<style scoped>

</style>