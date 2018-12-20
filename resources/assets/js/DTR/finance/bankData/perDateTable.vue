<template>
    <div>
        <table id="bank-table" class="table table-condensed table-hover" :selectedDate="dateSelected">
            <thead>
            <tr>
                <th v-for="(head, index) in header">{{head}}</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="bankof =='MB' || bankof=='MBTC'" v-for="(data,index) in datas">
                <td>{{data.bank_date}}</td>
                <td>{{data.check_no}}</td>
                <td>{{data.trans_des}}</td>
                <td style="text-align:right">{{data.type_amount=="AP" ? data.bank_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g,',') : ""}}</td>
                <td style="text-align:right">{{data.type_amount=="AR" ? data.bank_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g,',') : ""}}</td>
                <td style="text-align:right">{{data.bank_balance.toString().replace(/\B(?=(\d{3})+(?!\d))/g,',')}}</td>
                <td>{{data.branch}}</td>
            </tr>

            <tr v-if="bankof =='BPI'" v-for="(data,index) in datas">
                <td>{{data.bank_date}}</td>
                <td>{{data.check_no}}</td>
                <td>{{data.sba_ref_no}}</td>
                <td>{{data.branch}}</td>
                <td>{{data.trans_code}}</td>
                <td>{{data.trans_des}}</td>
                <td style="text-align:right">{{data.type_amount=="AP" ? data.bank_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g,',') : ""}}</td>
                <td style="text-align:right">{{data.type_amount=="AR" ? data.bank_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g,',') : ""}}</td>
                <td style="text-align:right">{{data.bank_balance.toString().replace(/\B(?=(\d{3})+(?!\d))/g,',')}}</td>
            </tr>

            <tr v-if="bankof=='LBP'" v-for="(data,index) in datas">
                <td>{{data.bank_date}}</td>
                <td>{{data.trans_des}}</td>
                <td style="text-align:right">{{data.type_amount=="AP" ? data.bank_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g,',') : ""}}</td>
                <td style="text-align:right">{{data.type_amount=="AR" ? data.bank_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g,',') : ""}}</td>
                <td style="text-align:right">{{data.bank_balance.toString().replace(/\B(?=(\d{3})+(?!\d))/g,',')}}</td>
                <td>{{data.branch}}</td>
                <td>{{data.check_no}}</td>
            </tr>

            <tr v-if="bankof=='PNB'" v-for="(data,index) in datas">
                <td>{{data.bank_date}}</td>
                <td>{{data.bank_date}}</td>
                <td>{{data.branch}}</td>
                <td>{{data.trans_des}}</td>
                <td>{{data.check_no}}</td>
                <td style="text-align:right">{{data.type_amount=="AP" ? data.bank_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g,',') : ""}}</td>
                <td style="text-align:right">{{data.type_amount=="AR" ? data.bank_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g,',') : ""}}</td>
                <td style="text-align:right">{{data.bank_balance.toString().replace(/\B(?=(\d{3})+(?!\d))/g,',')}}</td>
            </tr>

            <tr v-if="bankof=='BDO'" v-for="(data,index) in datas">
                <td>{{data.bank_date}}</td>
                <td>{{data.branch}}</td>
                <td>{{data.trans_des}}</td>
                <td style="text-align:right">{{data.type_amount=="AP" ? data.bank_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g,',') : ""}}</td>
                <td style="text-align:right">{{data.type_amount=="AR" ? data.bank_amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g,',') : ""}}</td>
                <td style="text-align:right">{{data.bank_balance.toString().replace(/\B(?=(\d{3})+(?!\d))/g,',')}}</td>
                <td>{{data.check_no}}</td>
            </tr>
            </tbody>
        </table>

    </div>
</template>
<script>
    export default
    {
        props:['dateSelected','datas','header','bankof'],

    }
</script>