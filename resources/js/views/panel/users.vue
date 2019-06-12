<template>
    <div>
        <table class="table table-sm table-striped table-dark">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Avatar URL</th>
                    <th>Role</th>
                    <th>Registered at</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(user, key) in users">
                    <th>{{user.id}}</th>
                    <td><span>{{user.name}}</span></td>
                    <td><span>{{user.email}}</span></td>
                    <td><span>{{user.avatarURL}}</span></td>
                    <td><span>{{user.role.name}}</span></td>
                    <td><span>{{user.created_at}}</span></td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#mUser" @click="edit(key)">Edit</button>
                        <button class="btn btn-danger btn-sm" @click="deleteUser(user.id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="modal fade text-dark" id="mUser" tabindex="-1" role="dialog" aria-labelledby="mUserLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="/api/v1/role/update" accept-charset="UTF-8" @submit.prevent="update">
                        <div class="modal-header">
                            <h5 class="modal-title" id="mUserLabel">Edit User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input v-model="euser.name" type="text" class="form-control" id="name" placeholder="Name">
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="default">Email</label>
                                        <input v-model="euser.email" type="email" class="form-control" id="default">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="default">Role</label>
                                        <select class="form-control" v-model="euser.role_id">
                                            <option v-for="role in roles" v-bind:value="role.id">{{role.name}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                users: [],
                euser: {},
                roles: {}
            }
        },

        methods: {
            edit(id) {
                this.euser = this.users[id];
                this.euser.role_id = this.users[id].role.id;
            },
            update() {
                $('#mUser').modal('hide');
                axios.post('/api/v1/user/update', this.euser).then(({data}) => {
                    this.users = data.users;
                });
            },
            deleteUser(id) {
                axios.post('/api/v1/user/delete', { id: id }).then(({data}) => {
                    this.users = data.users;
                });
            }
        },

        created() {
            axios.get('/api/v1/user/all').then(({data}) => {
                if (data.status == "success")
                    this.users = data.users;
            });
            axios.get('/api/v1/role/all').then(({data}) => {
                if (data.status == "success")
                    this.roles = data.roles;
            });
        }
    }
</script>
