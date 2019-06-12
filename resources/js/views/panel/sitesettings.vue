<template>
    <div>
        <h3 class="text-center pt-3">Site Settings</h3>
        <hr>
        <form method="POST" action="/api/v1/updateSiteSettings" accept-charset="UTF-8" class="pt-3 mt-auto" @submit.prevent="submitHandler()">
            <div class="form-group">
                <div class="row">
                    <div class="col">
                        <label for="siteName">Site Name</label>
                        <input type="text" id="siteName" class="form-control" v-model="form.siteName" :placeholder="site.name">
                    </div>
                    <div class="col">
                        <label for="paypal">Paypal Link</label>
                        <input type="url" name="paypal" class="form-control" v-model="form.paypal" :placeholder="site.paypal">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col">
                        <label for="rDelay">DJ Request Delay</label>
                        <input type="number" id="rDelay" class="form-control" v-model="form.rDelay" :placeholder="request.djDelay">
                    </div>
                    <div class="col">
                        <label for="rADjDelay">AutoDJ Request Delay</label>
                        <input type="number" name="rADjDelay" class="form-control" v-model="form.rADjDelay" :placeholder="request.aDjDelay">
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
        <hr>
        <div class="row">
            <div class="col">
                <div class="card settings-card h-100">
                    <div class="card-header">Rules</div>
                    <div class="card-body d-flex flex-column">
                        <ul class="list-group list-group-flush scroll">
                            <div v-if="dj.rules.length">
                                <li class="list-group-item" v-for="(rule, key) in dj.rules" :key="key">
                                    <div class="row">
                                        <div class="col">
                                            {{rule.name}}
                                        </div>
                                        <div class="col ml-auto">
                                            <button type="button" class="btn btn-danger btn-sm ml-auto" @click="deleteItem('rule', rule.id)">Delete</button>
                                        </div>
                                    </div>
                                </li>
                            </div>
                            <li class="list-group-item" v-else>No Rules</li>
                        </ul>
                        <form method="POST" action="/api/v1/addrule" accept-charset="UTF-8" class="pt-3 mt-auto" @submit.prevent="submitHandler('rule')">
                            <div class="form-group">
                                <label for="rule"></label>
                                <input type="text" name="rule" class="form-control" placeholder="New Rule" v-model="rule">
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card settings-card h-100">
                    <div class="card-header">Banned Songs</div>
                    <div class="card-body d-flex flex-column">
                        <ul class="list-group list-group-flush scroll">
                            <div v-if="dj.bsongs.length">
                                <li class="list-group-item" v-for="(bsong, key) in dj.bsongs" :key="key">
                                    <div class="row">
                                        <div class="col">
                                            {{bsong.name}}
                                        </div>
                                        <div class="col ml-auto">
                                            <button type="button" class="btn btn-danger btn-sm ml-auto" @click="deleteItem('bsong', bsong.id)">Delete</button>
                                        </div>
                                    </div>
                                </li>
                            </div>
                            <li class="list-group-item" v-else>No Banned Songs</li>
                        </ul>
                        <form method="POST" action="/api/v1/addbsong" accept-charset="UTF-8" class="pt-3 mt-auto" @submit.prevent="submitHandler('bsong')">
                            <div class="form-group">
                                <label for="rule"></label>
                                <input type="text" name="rule" class="form-control" placeholder="New Rule" v-model="bsong">
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card settings-card h-100">
                    <div class="card-header">Info</div>
                    <div class="card-body">
                        <form class="h-100" @submit.prevent="submitHandler()">
                            <div class="h-100 pb-5">
                                <div class="form-group h-100 pb-5 m-0">
                                    <label for="info">Change Info</label>
                                    <textarea class="form-control h-100" id="info" rows="4" v-model="form.info"></textarea>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Submit Changes</button>
                                </div>
                            </div>
                        </form>
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
                dj: {
                    rules: [],
                    bsongs: []
                },

                site: {
                    name: "",
                    paypal: ""
                },

                request: {
                    aDjDelay: 0,
                    djDelay: 0
                },

                form: {
                    siteName: "",
                    paypal: "",
                    rDelay: 0,
                    rADjDelay: 0,
                    info: ""
                },

                rule: "",
                bsong: ""
            }
        },

        methods: {
            submitHandler(type) {
                if (type)
                    axios.post('/api/v1/settings/add'+type, { name: this[type] }).then(({data}) => {
                        this.dj[type+'s'] = data[type+'s'];
                    }).catch(({response}) => console.log(response));
                else
                    axios.post('/api/v1/settings/update', this.form).then(({data}) => {
                        if (!data.status == "success")
                            return;

                        data = data.settings;

                        this.form.info = data.info;

                        this.site.name = data.siteName;
                        this.site.paypal = data.paypal;

                        this.request.aDjDelay = data.requestAutoDJDelay;
                        this.request.djDelay = data.requestDelay;
                        Object.keys(this.form).forEach(key => this.form[key] = null)
                    })//.catch(({response}) => console.log(response));

            },
            deleteItem(type, id) {
                axios.post('/api/v1/settings/del'+type, { id: id }).then(({data}) => {
                    this.dj[type+'s'] = data[type+'s'];
                    this[type] = "";
                }).catch(({response}) => console.log(response));
            }
        },

        created() {
            axios.get('/api/v1/settings/get').then(({data}) => {
                this.dj.rules = data.rules;
                this.dj.bsongs = data.bsongs;
                this.form.info = data.info;

                this.site.name = data.siteName;
                this.site.paypal = data.paypal;

                this.request.aDjDelay = data.requestAutoDJDelay;
                this.request.djDelay = data.requestDelay;
            });
        }
    }
</script>
