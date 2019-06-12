<template>
    <div v-if="$auth.ready()">
        <navbar :site-name="site.name" :site-img="site.img"></navbar>
        <player></player>

        <main>
            <router-view></router-view>
        </main>
    </div>
</template>
<script>
    import navbar from '../../components/nav'
    import player from '../../components/player'
    export default {
        props: ['site'],
        components: {
            navbar,
            player
        }
    }
</script>
