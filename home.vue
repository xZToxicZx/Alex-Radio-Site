<template>
    <div class="container mt-3">
        <div class="row">
            <div class="col-lg-8 text-center align-self-center h-100">
                <!-- <h1>TreeHouseRadios</h1> -->
                <div id="adBanners" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#adBanners" data-slide-to="0" class="active"></li>
                        <li data-target="#adBanners" data-slide-to="1"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img class="d-block w-100" src="/storage/banners/Heartbeat_UTH_Banner_2019_v2.png" height="410" alt="First slide">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="/storage/banners/poster_for_2020_UPDATED.jpg" height="410" alt="Second slide">
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#adBanners" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#adBanners" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <a :href="this.paypal" class="btn btn-dark mb-3 w-50"><i class="fab fa-paypal"></i> Donate</a>
                <a class="btn btn-dark mb-3 w-50" href="https://www.facebook.com/TreeHouseRadios/"><i class="fab fa-facebook-square"></i></a>
                <a class="btn btn-dark mb-3 w-50" href="https://www.twitch.tv/treehouseradios"><i class="fab fa-twitch"></i></a>
                <a class="btn btn-dark mb-3 w-50" href="https://discord.treehouseradios.com"><i class="fab fa-discord"></i></a>
            </div>
        </div>
        <h3>Recent Songs:</h3>
        <hr>
        <div class="row mt-3 scrollh">
            <div class="col-lg-3" v-for="song, id in recentsongs">
                <div class="row text-center">
                    <div class="col-3 p-0">
                        <img class="rounded-circle" :src="song.img" width="50" height="50">
                    </div>
                    <div class="col-9">
                        <div class="my-2" :id="'recentsong' + id">
                            <h5 :class="'m-0'" ref="el">{{song.name}}</h5>
                            <hr class="m-0 bg-light">
                            <h6 class="m-0">{{song.artist}}</h6>
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
                paypal: "",
                interval: null,
                recentsongs: []
            }
        },

        methods: {
            updateSongs() {
                axios.get('/api/v1/song/recent')
                .then(({data}) => {
                    this.recentsongs = data;
                });
            },
        },

        created() {
            this.updateSongs();
            this.interval = setInterval(() => this.updateSongs(), 20000);
            axios.get('/api/v1/settings/get')
            .then(({data}) => {
                this.paypal = data["paypal"];
            }).catch(err => console.log(err));
        },

        updated() {
            for (var i = this.recentsongs.length - 1; i >= 0; i--) {
                if ($('#recentsong'+i).children()[0].scrollWidth > $('#recentsong'+i)[0].offsetWidth && !$('#recentsong'+i).hasClass('textscroll'))
                    $('#recentsong'+i).addClass('textscroll');
                else if ($('#recentsong'+i).children().first().width() <= $('#recentsong'+i).width())
                    $('#recentsong'+i).removeClass('textscroll');
            }
        },

        beforeDestroy() {
            clearInterval(this.interval);
        }
    }
</script>
