<template>
    <div class="row">
        <div class="col-4">
            <div class="card">
                <div class="card-header text-center">Rules</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush" v-if="rules.length">
                        <li class="list-group-item" v-for="rule in rules">{{rule.name}}</li>
                    </ul>
                    <span v-else>No Rules</span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-header text-center">Banned Songs</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush" v-if="bsongs.length">
                        <li class="list-group-item" v-for="bsong in bsongs">{{bsong.name}}</li>
                    </ul>
                    <span v-else>No Banned Songs</span>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card">
                <div class="card-header text-center">Info</div>
                <div class="card-body">
                    {{info}}
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import shared from '../../shared'
    export default {
        data() {
            return {
                rules: [],
                bsongs: [],
                info: ""
            }
        },

        created() {
            axios.get('/api/v1/settings/get').then(({data}) => {
                this.rules = data['rules'];
                this.bsongs = data['bsongs'];
                this.info = data['info'];
            });
        }
    }
</script>
