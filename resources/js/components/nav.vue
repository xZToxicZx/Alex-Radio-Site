<template>
    <div>
        <nav class="navbar navbar-expand-md navbar-dark nav-main">
            <div class="container">
                <router-link class="navbar-brand" to="/" exact>
                    <img class="rounded-circle img" src="/storage/logo.png">
                    {{ siteName }}
                </router-link>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!--<li class="nav-item align-self-center">
                            <router-link class="nav-link" to="news">News</router-link>
                        </li> -->
                        <li class="nav-item align-self-center">
                            <a class="btn nav-link" data-toggle="modal" data-target="#mRequest">Request</a>
                        </li>
                        <li class="nav-item align-self-center">
                            <router-link class="nav-link" to="team">Team</router-link>
                        </li>
                        <li class="nav-item align-self-center">
                            <router-link class="nav-link" to="schedule">Schedule</router-link>
                        </li>
                        <li class="nav-item align-self-center">
                            <router-link class="nav-link" to="partners">Partners</router-link>
                        </li>
                        <!--<li class="nav-item align-self-center">
                            <router-link class="nav-link" to="shop">Shop</router-link>
                        </li>-->
                        <!--<li class="nav-item align-self-center">
                            <router-link class="nav-link" to="advertisewithus">Advertise with us</router-link>
                        </li>-->

                        <!-- Authentication Links -->
                        <li v-if="$auth.check()" class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded-circle img" :src="$auth.user().avatarURL" width="30" height="30">
                                {{ $auth.user().name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <router-link class="dropdown-item" to="/settings">Settings</router-link>
                                <router-link class="dropdown-item" to="/panel" v-can:view="'panel'">DJ Panel</router-link>
                                <a class="dropdown-item" href="#" @click.prevent="$auth.logout()">Logout</a>
                            </div>
                        </li>
                        <li v-if="!$auth.check()" class="nav-item">
                            <router-link class="nav-link" to="/signin">Sign In</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="modal fade text-dark" id="mRequest" tabindex="-1" role="dialog" aria-labelledby="mRequestLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" accept-charset="UTF-8" @submit.prevent="requestHandler">
                        <div class="modal-header">
                            <h5 class="modal-title" id="mRequestLabel">Request</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div v-if="!$auth.check()" class="form-group">
                                <label for="name">Name</label>
                                <input v-model="request.name" type="text" class="form-control" id="name" placeholder="Name">
                            </div>
                            <div class="form-group">
                                <label for="song">Song</label>
                                <input v-model="request.song" type="text" class="form-control" id="song" placeholder="Artist - Title">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import shared from '../shared'
    export default {
        props: ['siteName', 'siteImg'],

        data() {
            return {
                request: {
                    name: "",
                    song: ""
                }
            }
        },

        methods: {
            requestHandler() {
                let app = this;
                if (this.$auth.check())
                    this.request.name = this.$auth.user().name;
                $('#mRequest').modal('hide');
                axios.post('/api/v1/request/send', {
                    name: app.request.name,
                    message: app.request.song
                }).then(({data}) => {
                    app.request.song = "";
                }).catch(({response}) => {
                    this.$notify({
                        group: 'alert',
                        type: 'error',
                        title: 'Error!',
                        text: 'We wernt able to process your request at this time! Please try again at a later date.',
                        duration: 10000
                    });
                });
            }
        }
    }
</script>
