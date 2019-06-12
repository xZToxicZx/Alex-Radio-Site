<template>
    <div>
        <button class="btn btn-primary w-100" data-toggle="modal" data-target="#mAddRole">Add Role</button>
        <table class="table table-sm table-striped table-dark mt-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Default</th>
                    <th>Priority</th>
                    <th>Created at</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(role, key) in roles" :key="key">
                    <th>{{role.id}}</th>
                    <td><span>{{role.name}}</span></td>
                    <td><span>{{role.default}}</span></td>
                    <td><span>{{role.priority}}</span></td>
                    <td><span>{{role.created_at}}</span></td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#mRole" @click="edit(key)">Edit</button>
                        <button class="btn btn-danger btn-sm" @click="rdelete(role.id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="modal fade text-dark" id="mRole" tabindex="-1" role="dialog" aria-labelledby="mRoleLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="/api/v1/role/update" accept-charset="UTF-8" @submit.prevent="update">
                        <div class="modal-header">
                            <h5 class="modal-title" id="mRoleLabel">Edit Role</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input v-model="erole.name" type="text" class="form-control" id="name" placeholder="Name">
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-check py-1">
                                        <input v-model="erole.default" type="checkbox" class="form-check-input" id="default">
                                        <label for="default" class="form-check-label">Default (this will remove the default from the current default role)</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="default">Priority</label>
                                        <input v-model="erole.priority" type="number" class="form-control" id="default">
                                    </div>
                                </div>
                            </div>
                            <div class="form-check" v-for="perm in perms">
                                <input v-model="erole.perms[perm]" type="checkbox" class="form-check-input" id="default">
                                <label for="default" class="form-check-label">{{perm}}</label>
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
        <div class="modal fade text-dark" id="mAddRole" tabindex="-1" role="dialog" aria-labelledby="mAddRoleLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="/api/v1/role/create" accept-charset="UTF-8" @submit.prevent="create">
                        <div class="modal-header">
                            <h5 class="modal-title" id="mAddRoleLabel">Add Role</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input v-model="newrole.name" type="text" class="form-control" id="name" placeholder="Name">
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-check py-1">
                                        <input v-model="newrole.default" type="checkbox" class="form-check-input" id="default">
                                        <label for="default" class="form-check-label">Default (this will remove the default from the current default role)</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="default">Priority</label>
                                        <input v-model="newrole.priority" type="number" class="form-control" id="default">
                                    </div>
                                </div>
                            </div>
                            <div class="form-check" v-for="perm in perms">
                                <input v-model="newrole.perms[perm]" type="checkbox" class="form-check-input" id="default">
                                <label for="default" class="form-check-label">{{perm}}</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create</button>
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
                roles: [],
                erole: {
                    id: 0,
                    name: "",
                    default: false,
                    priority: 0,
                    perms: {}
                },
                newrole: {
                    name: "",
                    default: false,
                    priority: 0,
                    perms: {}
                },
                perms: [
                    'all',
                    'view.panel',
                    'view.panel.home',
                    'view.panel.requests',
                    'view.panel.timetable',
                    'view.panel.settings',
                    'view.panel.editusers',
                    'view.panel.editroles',
                    'view.panel.sitesettings',
                    'delete.request',
                    'claim.slot',
                    'claim.dj.slot',
                    'unclaim.slot',
                    'unclaim.dj.slot',
                    'delete.user',
                    'update.user',
                    'delete.role',
                    'update.role',
                    'create.role',
                    'add.rule',
                    'delete.rule',
                    'add.bsong',
                    'delete.bsong',
                    'update.sitesettings'

                ]
            }
        },

        methods: {
            edit(id) {
                this.erole = this.roles[id];
                if (!this.erole.perms || Array.isArray(this.erole.perms))
                    this.erole.perms = {};

                this.perms.forEach((perm) => {
                    if (this.erole.perms[perm] === null)
                        this.erole.perms[perm] = false;
                });
            },
            update() {
                axios.post('/api/v1/role/update', this.erole).then(({data}) => {
                    $('#mRole').modal('hide');
                    this.roles = data.roles
                });
            },
            rdelete(id) {
                axios.post('/api/v1/role/delete', {id: id}).then(({data}) => this.roles = data.roles);
            },
            create() {
                $('#mAddRole').modal('hide');
                axios.post('/api/v1/role/create', this.newrole).then(({data}) => {
                    this.roles = data.roles
                });
            }
        },

        created() {
            axios.get('/api/v1/role/all').then(({data}) => {
                if (data.status == "success")
                    this.roles = data.roles;
            });
        }
    }
</script>
