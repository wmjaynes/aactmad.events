<template>

    <div class="card-content event-content">
        <div class="event-body">
            <figure v-if="event.EvemtImageURL" class="image is-pulled-right is-hidden-desktop">
                <img class="event-image" :src="event.EventImageURL">
            </figure>
            <div class='description'>
                <p class='event-description'>{{ event.EventDesc }}</p>
                <p v-if="event.EventTimeDesc">{{ event.EventTimeDesc }}</p>
                <p class='event-admission'>
                    <strong class='event-label'>Admission: </strong>{{ event.EventTickets }}
                </p>
                <p v-if="event.EmailLink || event.EventPhone">

                    <strong v-if="event.EmailLink">EMail:</strong>
                    <mailto-link :email="event.EventEmail"></mailto-link>
                    <span v-if="event.EventPhone" class='event-phone'><strong class='event-label'
                    >Phone: {{ event.EventPhone }}</strong></span>
                </p>
            </div>

            <event-flyer-link :event="event"></event-flyer-link>

            <location-map-link :event="event"></location-map-link>

            <p class='vcard event-org'>
                Information provided by:
                <a v-if="event.OrgURL" :href="event.OrgURL">{{ event.OrgName }}</a>
                <span v-if="!event.OrgURL">{{ event.OrgName }}</span>
            </p>

            <event-facebook-link :event="event"></event-facebook-link>
        </div>
    </div>

</template>

<script>
    export default {
        name: "event-body",
        props: ['event'],
        computed: {},
    }
</script>

<style scoped>

    .event-content {
        padding: .5rem 0rem .5rem 0.2rem;
    }

    .event-body {
        border-color: lightgray;
        border-style: solid;
        padding-left: 0.5rem;
        padding-bottom: 0.5rem;
        border-width: 0 0 1px 4px;
    }

</style>