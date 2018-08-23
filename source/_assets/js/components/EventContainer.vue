<template>

    <div class="card event-card">

        <div @click="toggleBody">
            <event-header :event="event"></event-header>
        </div>
        <div v-if="event.displayBody">
            <event-body :event="event"></event-body>
        </div>
    </div>


</template>

<script>
    export default {
        name: "event-container",
        props: ['event'],
        methods: {
            toggleBody() {
                this.event.displayBody = !this.event.displayBody;
                // this.show = !this.show;
            },
        },
    }
</script>

<style scoped>

    .event-card {
        padding-left: 1rem;
        margin-bottom: 0.2rem;
        box-shadow: 0px 0px;
    }

</style>