<template>
    <div class="text-center">
        <div class="mb-3">
            <button type="button" class="btn" @click="priorWeek"><i class="fas fa-chevron-left"></i></button>
            <span>{{selectedWeek}}</span>
            <button type="button" class="btn" @click="nextWeek"><i class="fas fa-chevron-right"></i></button>
        </div>
        <select v-can:claim="'dj.slot'" class="custom-select mb-3" v-model="claimUser">
            <option selected value="">{{$auth.user().name}}</option>
            <option v-for="user in users" v-if="user.id != $auth.user().id" :value="user.id">{{user.name}}</option>
        </select>
        <table class="table table-sm table-striped table-dark">
            <thead>
                <tr>
                    <th scope="col">Hour</th>
                    <th scope="col">Mon</th>
                    <th scope="col">Tue</th>
                    <th scope="col">Wed</th>
                    <th scope="col">Thur</th>
                    <th scope="col">Fri</th>
                    <th scope="col">Sat</th>
                    <th scope="col">Sun</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(days, hr) in slots">
                    <th scope="row" v-if="hr.toString().length < 2">0{{hr}}:00</th>
                    <th scope="row" v-else>{{hr}}:00</th>

                    <td v-for="slot in days">
                        <button type="button" class="btn btn-danger btn-sm" v-if="slot.user != null && slot.user.id != $auth.user().id && canUnclaimDjSlot" @click="claim(slot.time, true, slot.user.id)">{{slot.user.name}}</button>
                        <button type="button" class="btn btn-danger btn-sm" v-else-if="slot.user != null && slot.user.id == $auth.user().id && (slot.time * 1000) > new Date().getTime() && canUnclaimSlot" @click="claim(slot.time, true)">Unclaim</button>
                        <button type="button" class="btn btn-primary btn-sm" v-else-if="slot.user == null && canClaimSlot" @click="claim(slot.time)">Claim</button>
                        <span v-else>{{slot.user ? slot.user.name : ''}}</span>
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
                slots: [],
                selectedWeek: "",
                weekOffset: 0,
                hourOffset: null,
                interval: null,
                users: [],
                claimUser: "",
                canUnclaimDjSlot: false,
                canUnclaimSlot: false,
                canClaimSlot: false
            }
        },

        methods: {
            getTimetable() {
                // Gets the timezone diffrence from the local timezone to UTC. (I'm one hour behind UTC)
                let hourOffset = new Date().getTimezoneOffset()/60;
                if (hourOffset <= 0)
                    hourOffset = Math.abs(hourOffset);
                else
                    hourOffset = -hourOffset;
                this.hourOffset = hourOffset;

                axios.get('/api/v1/timetable/get', { params: {weekOffset: this.weekOffset, userOffset: hourOffset} }).then(({data}) => {
                    this.slots = data.slots;
                    this.selectedWeek = data.selectedWeek;
                });
            },
            nextWeek() {
                this.weekOffset++;
                this.getTimetable();
            },
            priorWeek() {
                this.weekOffset--;
                this.getTimetable();
            },
            claim(time, unclaim, unclaimUser) {
                let user;
                let url = '/api/v1/timetable/claim';
                if (unclaim)
                    url = '/api/v1/timetable/unclaim';
                if (unclaim && unclaimUser)
                    user = unclaimUser;
                else
                    user = this.claimUser

                axios.post(url, { timestamp: time, userOffset: this.hourOffset, user: user }).then(({data}) => {
                    // Check if success or not...
                    if (data.status == 'err')
                        return;
                    this.getTimetable();
                }).catch(err => console.log(err));
            }
        },

        created() {
            axios.get('/api/v1/user/all').then(({data}) => {
                if (!data.status == "success")
                    return;
                data.users.forEach((user, key) => {
                    let foundClaim = false;
                    user.role.perms.forEach(perm => {
                        if (perm.name == "claim.slot" || perm.name == "all")
                            foundClaim = true;
                    });
                    if (!foundClaim)
                        data.users.splice(key, 1);
                });
                this.users = data.users;
            });
            this.getTimetable();
            this.interval = setInterval(() => this.getTimetable(), 20000);
            this.canClaimSlot = this.$auth.user().role.perms.find(perm => {
                return (perm.name == "claim.slot" || perm.name == "all");
            });
            this.canUnclaimSlot = this.$auth.user().role.perms.find(perm => {
                return (perm.name == "unclaim.slot" || perm.name == "all");
            });
            this.canUnclaimDjSlot = this.$auth.user().role.perms.find(perm => {
                return (perm.name == "unclaim.dj.slot" || perm.name == "all");
            });
        },

        beforeDestroy() {
            clearInterval(this.interval);
        }
    }
</script>
