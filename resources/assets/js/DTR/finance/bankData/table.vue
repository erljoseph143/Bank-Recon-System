<template>
    <div>
        <div v-if="tableShow">
            <table id="montly-table"  class="table table-condensed table-hover">
                <thead>
                <tr>
                    <th v-for="(header, index) in header">{{header}}</th>
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
            <a :href="urlExcel" class="btn btn-success" style="margin-bottom: 10px;">
                <i class="glyphicon glyphicon-download"></i>
                Download as Excel
            </a>

        </div>

    </div>
</template>
<script>
    import { EventBus }  from '../../../event-bus.js';
    export default
    {
        props:['month','year'],
        data()
        {
            return {
                header:[],
                datas:[],
                bankof:'',
                date: null,
                yearNow:'',
                monthNow:'',
                tableShow:false,
                urlExcel:$baseUrl

            }
        },
        created()
        {
            this.tableShow = false;
            EventBus.$on('filter',data => {

                this.showMonthlyDTR(data[0],data[1]);

            });
        },
        mounted()
        {
           // this.urlExcel = this.$route.params.bankacct;
            // path:'/dtr/view/:banks/:bankacct/:com/:bu',

        },
        ready()
        {
            console.log(this.monthNow);
        },
        watch: {
            // if tid is updated, this will trigger... I Think :-D

            month: function (month) {
                this.monthNow = month;
            },
            year: function (year)
            {
               this.yearNow = year;
                this.showMonthlyDTR(this.monthNow,this.yearNow);
                //console.log(this.monthNow);
            }
        },
        methods:{
            filter()
            {
                this.showMonthlyDTR(this.monthNow,this.yearNow);
            },
            showMonthlyDTR(month,year)
            {
               // console.log(month);
                //this.tableShow = true;
                let bank      = this.$route.params.banks;
                let bankacct  = this.$route.params.bankacct;
                let com       = this.$route.params.com;
                let bu        = this.$route.params.bu;

                let $this     = this;
                this.bankof   = bank;
                this.urlExcel = $baseUrl+"/dtr/excel/"+bankacct+"/"+com+"/"+bu+"/"+month+"/"+year;
                if(bank=='MB' || bank=="MBTC")
                {
                    this.header = [
                        "Date",
                        "Check No.",
                        "Description",
                        "Debit",
                        "Credit",
                        "Balance",
                        "Branch"
                    ];

                    $this.tableShow     = false;
                    axios.get('dtr/dataBank/'+bankacct+'/'+com+'/'+bu+'/'+year+'/'+month).then( res => {

                        this.datas      = res.data;
                        $this.tableShow = true;
                        $(document).ready(function(){
                            $("#montly-table").DataTable();
                        });
                    });
                    //console.log(this.datas);
                }
                else if(bank=="BPI")
                {
                    this.header = [
                        "Date",
                        "Check Number",
                        "SBA Reference No.",
                        "Branch",
                        "Transaction Code",
                        "Transaction Description",
                        "Debit",
                        "Credit",
                        "Running Balance",
                    ];

                    $this.tableShow     = false;
                    axios.get('dtr/dataBank/'+bankacct+'/'+com+'/'+bu+'/'+year+'/'+month).then( res => {

                        this.datas     = res.data;
                        $this.tableShow = true;
                        $(document).ready(function(){
                            $("#montly-table").DataTable();
                        });
                    });
                }
                else if(bank=="LBP")
                {
                    this.header = [
                        "Date",
                        "Description",
                        "Debit",
                        "Credit",
                        "Balance",
                        "Branch",
                        "Cheque Number"
                    ];

                    $this.tableShow     = false;
                    axios.get('dtr/dataBank/'+bankacct+'/'+com+'/'+bu+'/'+year+'/'+month).then( res => {

                        this.datas      = res.data;
                        $this.tableShow = true;
                        $(document).ready(function(){
                            $("#montly-table").DataTable();
                        });
                    });
                }
                else if(bank=="PNB")
                {
                    this.header = [
                        "Post Date",
                        "Value Date",
                        "NEG BR",
                        "Transaction Description",
                        "Check/ Seq No",
                        "Withdrawals",
                        "Deposits",
                        "Balance"
                    ];
                    $this.tableShow     = false;
                    axios.get('dtr/dataBank/'+bankacct+'/'+com+'/'+bu+'/'+year+'/'+month).then( res => {
                        this.datas      = res.data;
                        $this.tableShow = true;
                        $(document).ready(function(){
                            $("#montly-table").DataTable();
                        });
                    });
                }
                else if(bank=="BDO")
                {
                    this.header = [
                        "Posting Date",
                        "Branch",
                        "Description",
                        "Debit",
                        "Credit",
                        "Running Balance",
                        "Check Number"
                    ];
                    $this.tableShow     = false;
                    axios.get('dtr/dataBank/'+bankacct+'/'+com+'/'+bu+'/'+year+'/'+month).then( res => {
                        this.datas      = res.data;
                        $this.tableShow = true;
                        $(document).ready(function(){
                            $("#montly-table").DataTable();
                        });
                    });
                }

            }
        }
    }
</script>