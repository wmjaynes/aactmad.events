<template>
    <p v-if="event.EventPrURL">
        <a :href="event.EventPrURL">Publicity Flyer <span class="icon"><i class="fab fa-pied-piper-alt"></i></span></a>
    </p>
</template>

<script>
    export default {
        name: "event-flyer-link",
        props: ['event'],
    }
</script>

<style scoped>

</style>