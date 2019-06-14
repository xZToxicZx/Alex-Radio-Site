<template>
    <div class="container pt-3 text-center">
        <div class="mb-3">
            <button type="button" class="btn" @click="priorWeek"><i class="fas fa-chevron-left"></i></button>
            <span>{{selectedWeek}}</span>
            <button type="button" class="btn" @click="nextWeek"><i class="fas fa-chevron-right"></i></button>
        </div>
        <table class="table table-sm table-striped table-dark">
            <thead>
                <tr>
                    <th scope="col-3">Hour</th>
                    <th scope="col-3">Mon</th>
                    <th scope="col-3">Tue</th>
                    <th scope="col-3">Wed</th>
                    <th scope="col-3">Thur</th>
                    <th scope="col-3">Fri</th>
                    <th scope="col-3">Sat</th>
                    <th scope="col-3">Sun</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(slotsr, hr) in slots">
                    <th scope="row" v-if="hr.toString().length < 2">0{{hr}}:00</th>
                    <th scope="row" v-else>{{hr}}:00</th>

                    <td v-for="slot in slotsr">
                        <span v-if="slot.user">{{slot.user.name}}</span>
                        <span v-else></span>
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
                weekOffset: 0
            }
        },

        methods: {
            getTimetable() {
                let d = new Date();
                let hourOffset = d.getTimezoneOffset()/60; // gets the timezone diffrence from the local timezone to UTC. (I'm one hour behind UTC)

                if (hourOffset <= 0)
                    hourOffset = Math.abs(hourOffset);
                else
                    hourOffset = -hourOffset;

                axios.get('/api/v1/timetable/get', { params: {weekOffset: this.weekOffset, userOffset: hourOffset} }).then(({data}) => {
                    this.slots = data['slots'];
                    this.selectedWeek = data['selectedWeek'];
                });
            },
            nextWeek() {
                this.weekOffset++;
                this.getTimetable();
            },
            priorWeek() {
                this.weekOffset--;
                this.getTimetable();
            }
        },

        mounted() {
            this.getTimetable();
        }
    }
</script>

<!--
    slots: {

    }
-->
