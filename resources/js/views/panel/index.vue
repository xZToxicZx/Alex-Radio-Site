<template>
    <div>
        <div v-if="$auth.ready()">
            <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
                <router-link to="/panel/" class="navbar-brand col-md-2 mr-0">{{site.name}}</router-link>
                <ul class="navbar-nav px-3">
                    <li class="nav-item text-nowrap">
                        <router-link to="/" class="nav-link">Exit Panel</router-link>
                    </li>
                </ul>
            </nav>
            <div class="container-fluid">
                <div class="row">
                    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                        <div class="sidebar-sticky">
                            <ul class="nav flex-column">
                                <li class="nav-item" v-can:view="'panel.requests'">
                                    <router-link to="/panel/requests" class="nav-link"><i class="fas fa-list-alt"></i> Requests</router-link>
                                </li>
                                <li class="nav-item" v-can:view="'panel.timetable'">
                                    <router-link to="/panel/timetable" class="nav-link"><i class="fas fa-table"></i> Timetable</router-link>
                                </li>
                                <li class="nav-item" v-can:view="'panel.settings'">
                                    <router-link to="/panel/settings" class="nav-link"><i class="fas fa-cog"></i> Settings</router-link>
                                </li>
                            </ul>
                            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted" v-show="showAdmin">
                                <span>Admin</span>
                            </h6>
                            <ul class="nav flex-column" id="adminNav">
                                <li class="nav-item" v-can:view="'panel.users'">
                                    <router-link to="/panel/users" class="nav-link"><i class="fas fa-users"></i> Edit Users</router-link>
                                </li>
                                <li class="nav-item" v-can:view="'panel.roles'">
                                    <router-link to="/panel/roles" class="nav-link"><i class="fas fa-user-tag"></i> Edit Roles</router-link>
                                </li>
                                <li class="nav-item" v-can:view="'panel.sitesettings'">
                                    <router-link to="/panel/sitesettings" class="nav-link"><i class="fas fa-cogs"></i> Site Settings</router-link>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-5">
                        <router-view></router-view>
                    </main>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import shared from '../../shared'
    export default {
        props: ['site'],
        data() {
            return {
                showAdmin: true
            }
        },
        updated() {
            if ($('#adminNav').children().length != 0)
                this.showAdmin = true;
            else
                this.showAdmin = false;
        }
    }
</script>
