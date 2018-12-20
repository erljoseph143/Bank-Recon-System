<template>
    <div>
        <ul class="page-sidebar-menu page-sidebar-menu-light page-sidebar-menu-closed" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <li class="start active link" id="dash-board">
                <router-link to="home#" exact>
                    <i class="icon-home"></i>
                    <span class="title">Dashboard</span>
                    <span class="selected"></span>
                </router-link>
            </li>
        </ul>
    </div>
</template>
<script>
    export default {

    }
</script>