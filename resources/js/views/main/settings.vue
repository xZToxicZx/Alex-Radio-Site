<template>
    <div class="container">
        <div class="card text-white bg-danger mt-3" v-if="!success && success !== null">
            <p class="card-body m-0" v-if="errors">Error: one or more of the below field(s) is invalid.</p>
            <p class="card-body m-0" v-else>Error: We cannot process your request at the moment. If the error persists, please contact management.</p>
        </div>
        <div class="card text-white bg-success mt-3" v-else-if="success">
            <p class="card-body m-0">Success: Your profile has been updated successfully!</p>
        </div>
        <form method="POST" action="/api/v1/updateSiteSettings" accept-charset="UTF-8" class="pt-3 mt-auto" @submit.prevent="submitHandler()">
            <div class="form-group">
                <div class="row">
                    <div class="col">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" :class="{ 'border-danger' : !success && errors.email }" aria-describedby="nameHelp" v-model="input.name" :placeholder="$auth.user().name">
                        <small id="nameHelp" class="form-text text-muted" v-if="errors.name">{{errors.name[0]}}</small>
                    </div>
                    <div class="col">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" :class="{ 'border-danger' : !success && errors.email }" aria-describedby="emailHelp" v-model="input.email" :placeholder="$auth.user().email">
                        <small id="emailHelp" class="form-text text-muted" v-if="errors.email">{{errors.email[0]}}</small>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col">
                        <label for="avatar">Avatar</label>
                        <input type="file" name="avatar" class="form-control-file" :class="{ 'border-danger border rounded' : !success && errors.avatar }" accept="image/*" aria-describedby="avatarHelp" @change="input.avatar = $event.target.files[0]">
                        <small id="avatarHelp" class="form-text text-muted" v-if="errors.avatar">{{errors.avatar[0]}}</small>
                        <small id="avatarHelp" class="form-text text-muted" v-else>Dimension minimums: 120px x 120px. Ratio: 1/1</small>
                    </div>
                    <div class="col">
                        <label for="password">Current Password</label>
                        <input type="password" name="password" class="form-control" :class="{ 'border-danger' : !success && errors.password }" aria-describedby="passwordHelp" v-model="input.password">
                        <small id="passwordHelp" class="form-text text-muted" v-if="errors.password">{{errors.password[0]}}</small>
                        <small id="passwordHelp" class="form-text text-muted" v-else>Required</small>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col">
                        <label for="newpass">New Password</label>
                        <input type="password" name="newpass" class="form-control" :class="{ 'border-danger' : !success && errors.new_password }" aria-describedby="newpassHelp" v-model="input.new_password">
                        <small id="newpassHelp" class="form-text text-muted" v-if="errors.new_password">{{errors.new_password[0]}}</small>
                    </div>
                    <div class="col">
                        <label for="password">Confirm Password</label>
                        <input type="password" name="newpassconf" class="form-control" :class="{ 'border-danger' : !success && errors.new_password }" aria-describedby="newpassconfHelp" v-model="input.new_password_confirmation">
                        <small id="newpassconfHelp" class="form-text text-muted" v-if="errors.new_password">{{errors.new_password[0]}}</small>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                input: {
                    name: "",
                    email: "",
                    avatar: null,
                    password: "",
                    new_password: "",
                    new_password_confirmation: ""
                },
                errors: [],
                success: null
            }
        },

        methods: {
            submitHandler() {
                let update = new FormData();
                Object.keys(this.input).forEach((key) => {
                    if (this.input[key])
                        update.append(key, this.input[key]);
                    this.input[key] = null;
                });
                axios.post('/api/v1/user/update', update, { headers: {'Content-Type': 'multipart/form-data' }}).then(({data}) => {
                    this.success = true;
                    this.$auth.user(data.user);
                }).catch(({response}) => {
                    let err = response.data;
                    this.success = false;
                    this.errors = err.errors;
                });
            }
        }
    }
</script>
