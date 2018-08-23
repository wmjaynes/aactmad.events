<template>
    <a v-bind:href="mailto" >{{ email }}</a>
</template>

<script>
    export default {
        name: "mailto-link",
        props: ['email'],
        computed: {
            mailto() {
                return 'mailto:' + this.email;
            }
        },
    }
</script>

<style scoped>

</style>