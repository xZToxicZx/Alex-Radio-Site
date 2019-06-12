<template>
    <div class="text-center">
        <div class="jumbotron jumbotron-fluid p-5">
            <div class="container">
                <h1 class="display-4">Register</h1>
            </div>
        </div>
        <div class="container" v-if="has_error && !success">
            <div class="card text-white bg-danger">
                <p class="card-body" v-if="error == 'registration_validation_error'">Error: one or more of the below field(s) is invalid.</p>
                <p class="card-body" v-else>Error: We cannot process your request at the moment. If the error persists, please contact management.</p>
            </div>
        </div>
        <form autocomplete="off" @submit.prevent="submitHandler" v-if="!success" method="post" class="w-50 mx-auto">
            <div class="form-group">
                <label for="disName">Display Name</label>
                <input type="text" class="form-control" id="disName" placeholder="Name" v-model="name">
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" :class="{ 'border-danger' : has_error && errors.email }" id="email" aria-describedby="emailHelp" placeholder="Enter email" v-model="email">
                <small id="emailHelp" v-if="has_error && errors.email" class="form-text text-muted">{{ errors.email }}</small>
                <small id="emailHelp" v-else class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="pass">Password</label>
                <input type="password" class="form-control" :class="{ 'border-danger' : has_error && errors.password }" id="pass" placeholder="Password" v-model="pass">
            </div>
            <div class="form-group">
                <label for="confpass">Confirm Password</label>
                <input type="password" class="form-control" :class="{ 'border-danger' : has_error && errors.password }" id="confpass" placeholder="Confirm Password" v-model="confpass">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <div>
                <small><router-link to="signin">Sign In</router-link></small>
            </div>
        </form>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                name: "",
                email: "",
                pass: "",
                confpass: "",
                has_error: false,
                error: "",
                errors: {},
                success: false
            }
        },

        methods: {
            submitHandler() {
                var app = this
                this.$auth.register({
                    data: {
                        name: app.name,
                        email: app.email,
                        password: app.pass,
                        password_confirmation: app.confpass
                    },
                    success: function () {
                        app.success = true
                    },
                    error: function (err) {
                        app.has_error = true;
                        app.error = err.response.data.error;
                        app.errors = err.response.data.errors || {}
                    }
                })
            }
        }
    }
</script>
