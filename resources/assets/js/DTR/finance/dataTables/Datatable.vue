<template>
    <table class="table is-bordered data-table">
        <thead>
        <tr>

        </tr>
        </thead>
        <slot></slot>
    </table>
</template>

<script>
    export default {
        props: ['columns', 'sortKey', 'sortOrders']
    }
</script>