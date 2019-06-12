<template>
    <div class="text-center">
        <div class="jumbotron jumbotron-fluid p-5">
            <div class="container">
                <h1 class="display-4">Sign In</h1>
            </div>
        </div>
        <div class="container" v-if="has_error">
            <div class="card bg-danger text-white">
                <p class="card-body" v-if="error != 'login_error'">Error: We cannot currently authenticate your details. Please try again later.</p>
                <p class="card-body" v-else>Error: The details you have entered are incorrect.</p>
            </div>
        </div>
        <form method="POST" action="/api/v1/login" accept-charset="UTF-8" class="w-50 mx-auto" @submit.prevent="submitHandler">
            <div class="form-group">
                <label for="email">Email address</label>
                <input v-model="email" type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input v-model="pass" type="password" class="form-control" id="password" placeholder="Password">
            </div>
            <div class="form-check">
                <input v-model="rem" type="checkbox" class="form-check-input" id="remeber">
                <label class="form-check-label" for="remeber">Remember Me</label>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <div class="">
                <small><router-link to="register">Register</router-link></small>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                email: '',
                pass: '',
                rem: false,
                has_error: false,
                error: null
            }
        },

        methods: {
            submitHandler() {
                let redirect = this.$auth.redirect();
                let app = this;

                this.$auth.login({
                    data: {
                        email: app.email,
                        password: app.pass
                    },
                    success: function() {
                        const redirectTo = redirect

                        this.$router.push({name: redirectTo});
                    },
                    error: function(err) {
                        app.has_error = true
                        app.error = err.response.data.error;
                    },
                    remeberMe: app.rem,
                    fetchUser: true
                })
            }
        },

        created() {
        }
    }
</script>
