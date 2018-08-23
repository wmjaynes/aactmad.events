<template>
    <p v-if="event.EventFacebookURL">
        <a target="_blank" :href="event.EventFacebookURL">Facebook Event <span
                class="icon"><i class="fab fa-facebook-square"></i></span></a>
    </p>
</template>

<script>
    export default {
        name: "event-facebook-link",
        props: ['event'],
    }
</script>

<style scoped>

</style>