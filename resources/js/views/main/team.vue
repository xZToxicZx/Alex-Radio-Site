<template>
    <div class="text-center">
        <div class="jumbotron jumbotron-fluid p-5">
            <div class="container">
                <h1 class="display-4">Team</h1>
            </div>
        </div>
        <div class="container">
            <div v-for="users, name in roles">
                <div v-if="users.length > 0">
                    <h1>{{name.replace(/_/g, ' ')}}</h1>
                    <hr>
                    <div class="row">
                        <div class="col-sm pb-3" v-for="user in users">
                            <img class="rounded-circle" :src="user.img" width="128" height="128">
                            <h4 class="pt-3">{{user.name}}</h4>
                            <div class="socials">
                                <a class="social" :href="url" v-for="(url, social) in user.socials"><i :class="'fab fa-'+social"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                roles: {}
            }
        },

        created() {
            axios.get('/api/v1/team')
                .then(({data}) => this.roles = data)
        }
    }
</script>
