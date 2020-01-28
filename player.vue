<template>
    <div class="bg-dark text-white-50">
        <div class="container py-3">
            <div class="row">
                <div class="col-sm-4 align-self-center">
                    <div class="row">
                        <div class="col-sm-14">
                            <canvas id="audio-visualiser" width="80" height="80"></canvas>
                            <img class="rounded-circle img m-2 spin" :class="player.audio.paused ? 'anim-pause' : ''" :src="song.img" width="50" height="50">
                            <i @click="playPause" class="playpauseicon fa" :class="player.icon"></i>
                            <input type="range" orient="horizontal" max="1" min="0.01" step="0.01" v-model="player.volume" @input="updateVolume" @change="updateVolume" />
                        </div>
                    </div>
                </div>
                <div id="songinfo" class="col-sm-4 text-center align-self-center">
                    <h4 class="m-0">{{song.name}}</h4>
                    <hr class="m-0 bg-light">
                    <p class="m-0">{{song.artist}}</p>
                </div>
                <div class="col-sm-4 text-center align-self-center">
                    <div class="row">
                        <div class="col text-center h-100">
                            <h5 class="my-2">Listeners: {{radio.listeners}}</h5>
                        </div>
                        <div class="col">
                            <h5 class="my-2">Live DJ: {{radio.dj}}</h5>
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
                radio: {
                    listeners: "Loading...",
                    dj: "Loading...",
                },
                song: {
                    artist: '',
                    name: '',
                    img: '',
                    interval: null
                },
                player: {
                    stream: 'https://source.treehouseradios.com:8000/stream',
                    volume: 0.05,
                    icon: 'pause',
                    audio: new Audio(),
                    icon: 'pause',
                    spin: '',
                    visualiser: new AudioContext()
                }
            };
        },

        methods: {
            playPause() {
                if (!this.player.audio.paused) {
                    this.player.icon = 'fa-play';
                    this.player.audio.pause();
                    this.player.audio.src = "";
                    this.player.audio.load();
                    return
                }
                this.player.icon = 'fa-pause';
                this.player.audio.src = this.player.stream;
                this.player.audio.load();
                this.player.audio.play();
                this.player.visualiser.resume();
            },
            updateVolume() {
                this.player.audio.volume = this.player.volume;
            },
            updateInfo() {
                axios.get('/api/v1/song/current')
                .then(({data}) => {
                    this.song.artist = data["artist"];
                    this.song.name = data["name"];
                    this.song.img = data["img"];
                })
                .catch(err => {
                    if (!err)
                        console.log("network problem");
                    else
                        console.log(err);
                });
                axios.get('/api/v1/radio/listeners').then(({data}) => {
                    this.radio.listeners = data.listeners;
                });
                axios.get('/api/v1/timetable/currentSlot').then(({data}) => {
                    this.radio.dj = data.live;
                });
            }
        },

        created() {
            this.updateInfo();
            this.song.interval = setInterval(() => this.updateInfo(), 20000);
            this.player.audio.src = this.player.stream;
            this.player.audio.volume = this.player.volume;
            this.player.audio.crossOrigin = "anonymous";
            //this.player.visualiser = new AudioContext();
        },

        mounted() {
            let audioC = this.player.visualiser;
            let src = audioC.createMediaElementSource(this.player.audio);
            let analyser = audioC.createAnalyser();

            let c = document.getElementById("audio-visualiser");
            let ctx = c.getContext("2d"); // Sets the canvas to 2d

            src.connect(analyser);
            src.connect(audioC.destination);

            var dataArray = new Uint8Array(analyser.frequencyBinCount);

            let w = c.width, h = c.height;

            function renderFrame() {
                requestAnimationFrame(renderFrame);

                analyser.getByteFrequencyData(dataArray);

                let bars = 325;
                let angle = 2*Math.PI/bars;
                ctx.clearRect(0, 0, w, h);
                ctx.fillStyle = 'rgba(255,255,255,1)';
                ctx.save();
                ctx.translate(w / 2, h / 2);
                let i = 0;
                while (i < bars) {
                    ctx.rotate(angle);
                    let val = dataArray[i] / 256 * 15;
                    ctx.fillRect(-1, 25,0.5, val);
                    i++;
                }
                ctx.restore();

            }
            renderFrame();

            this.player.audio.play()
            .then(() => {
                this.player.icon = 'fa-pause';
                this.player.spin = '';
            })
            .catch(err => {
                this.player.icon = 'fa-play';
                this.player.audio.src = "";
                this.player.audio.load();
                this.$notify({
                    group: 'alert',
                    type: 'error',
                    title: 'Autoplay Failed!',
                    text: 'The player failed to start! Please click the play button to start the player.',
                    duration: 10000
                });
            });
        },

        updated() {
            if ($('#songinfo').children()[0].scrollWidth > $('#songinfo')[0].offsetWidth && !$('#songinfo').hasClass('textscroll'))
                $('#songinfo').addClass('textscroll');
            else if ($('#songinfo').children().first().width() <= $('#songinfo').width())
                $('#songinfo').removeClass('textscroll');
        },

        beforeDestroy() {
            clearInterval(this.song.interval);
            this.player.audio.pause();
            this.player.audio.src = "";
            this.player.audio.load();
        }
    }
</script>
