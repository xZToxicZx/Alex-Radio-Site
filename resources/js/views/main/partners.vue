<template>
    <div class="text-center">
        <div class="jumbotron jumbotron-fluid p-5">
            <div class="container">
                <h1 class="display-4">Partners</h1>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm pb-3" v-for="partner in partners">
                    <img class="rounded-circle" :src="partner.img" width="120" height="120">
                    <h4 class="pt-3">{{partner.name}}</h4>
                    <div class="socials">
                        <a class="social" :href="'https://'+url" v-for="(url, social) in partner.socials"><i :class="'fab fa-'+social" v-if="social != 'website'"></i><i class="fas fa-globe" v-else></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script >
    export default {
        data() {
            return {
                partners: [
                    {
                        name: 'HeartbeatUTH',
                        img: 'https://cdn.discordapp.com/icons/418356506103382028/b935c653c4c7202a09589eaa887baaaa.jpg?size=256',
                        socials: {
                            website: "heartbeatuth.com",
                            discord: "discord.heartbeatuth.com"
                        }
                    }
                ]
            }
        }
    }
</script>
