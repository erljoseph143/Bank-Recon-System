import VueRouter from 'vue-router';

let routes = [
/*
* Finance Route
* */
    {
        path:'/home',
        name:'home',
        component:require('./DTR/finance/Bank.vue')
    },
    {
        path:'/banks/:bank',
        name:'banks',
        component:require('./DTR/finance/bankTable')
    },
    {
        path:'/dtr/form/:banks/:bankacct/:com/:bu',
        component:require('./DTR/finance/form/form.vue')
    },
    {
        path:'/dtr/view/:banks/:bankacct/:com/:bu',
        component:require('./DTR/finance/bankData/dtrData.vue')
    },

    /*
    * Accounting Route
    * */

    {
        path:'/accounting/banks/:bank',
        component:require('./DTR/accounting/bankTable.vue')
    }

    // {
    //     path:'/dtr/view/:banks/:bankacct/:com/:bu',
    //     component:require('./DTR/finance/bankData/table.vue')
    // }
];
//('{{url('dtr/form')}}'+'/'+bank+'/'+bankacct+'/'+com+'/'+bu)
export default new VueRouter({
    routes
});