<template>
    <MainLayout ref="menulayout"/>
    <router-view></router-view>
</template>
<script>

import MainLayout from "./views/layouts/MainLayout.vue";

export default {
    name: "App",
    mounted() {
    },
    components: {MainLayout},
    methods: {
        userLogged(user) {
            let child = this.$refs.menulayout;
            child.user = user;
        }
    }
}
</script>
<style>
</style>
