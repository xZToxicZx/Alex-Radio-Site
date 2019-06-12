<template>
    <div>
        <h3 class="text-center pt-3">Requests</h3>
        <hr>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Message</th>
                    <th scope="col" v-can:delete="'request'"><button class="btn btn-danger btn-sm" @click="deleteReq()">Delete All</button></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(request, index) in requests">
                    <th scope="row">{{index + 1}}</th>
                    <td>{{ request.name }}</td>
                    <td>{{ request.message }}</td>
                    <td v-can:delete="'request'">
                        <button type="button" class="btn btn-danger btn-sm ml-auto" @click="deleteReq(request.id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                requests: [],
                interval: null
            }
        },

        methods: {
            deleteReq(id) {
                axios.post('/api/v1/request/delete', { id: id }).then(({data}) => {
                    if (data.status == "success")
                        this.requests = data.requests;
                }).catch(err => console.log(err.response))
            },
            getReqs() {
                axios.get('/api/v1/request/all').then(({data}) => {
                    this.requests = data;
                }).catch(err => console.log(err.response));
            }
        },

        created() {
            this.getReqs()
            this.interval = setInterval(() => this.getReqs(), 20000)
        },

        beforeDestroy() {
            clearInterval(this.interval);
        }
    }
</script>
