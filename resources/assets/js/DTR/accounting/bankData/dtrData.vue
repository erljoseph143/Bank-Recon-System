<template>
    <div>
        <tabs>
            <tab title="Calendar">
                <div class="form-group col-md-5">
                    <button type="button" class="btn btn-success ribbon">Bank: {{bankAcct}}</button>
                </div>
                <div class="form-group col-md-2">

                    <select name="months" class="form-control" ref="month"  id="months">
                        <option v-for="(calmonth,index) in months" :selected="index.replace(/\s/g,'') == dateMonth ? true : false" :value="index.replace(/\s/g,'')">{{calmonth}}</option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <select name="years" class="form-control" ref="year" id="years">
                        <option v-for="(year,index) in years" :selected="index.replace(/\s/g,'') == dateYear ? true : false" :value="index.replace(/\s/g,'')">{{year}}</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <button class="btn btn-default prev" @click="getPrevMonth" :disabled="disablePrev">
                        <i class="glyphicon glyphicon-arrow-left"></i>
                        Prev
                    </button>
                    <button id="prev" class="btn btn-default next" @click="getNextMonth" previous="hahahah" :disabled="disableNext">
                        <i class="glyphicon glyphicon-arrow-right"></i>
                        Next
                    </button>
                </div>
                
                <div id="calender_section">
                    <div id="calender_section_top">
                        <ul>
                            <li>Sun</li>
                            <li>Mon</li>
                            <li>Tue</li>
                            <li>Wed</li>
                            <li>Thu</li>
                            <li>Fri</li>
                            <li>Sat</li>
                        </ul>
                    </div>
                    <div id="calender_section_bot">
                        <ul v-for="(cal,index) in calendar">
                            <li v-for="(d,key) in cal.day" :class=" cal.liClass[key]+' date_cell'">
                               <span>
                                   {{d}}
                               </span>

                                <div class="record" :data-date="cal.dataDate[key]" style="margin-top:30px;color:blue" @click="showRecords" v-html="cal.records[key]">
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="clearfix"></div>
            </tab>
            <tab title="Tabular">
                <!--{{monthOf}}-->
                <monthly-table-acct  :month="monthOf" :year="yearOf"></monthly-table-acct>
            </tab>
        </tabs>

        <modal v-model="showModal" :title="bankAcct" size="lg modal-full" :backdrop="false">
            <bank-table-acct v-if="tableShow" :dateSelected="dateSelected" :datas="datasOf" :header="headerOf" :bankof="bankName"></bank-table-acct>
        </modal>
    </div>
</template>

<script>
    import { EventBus }  from '../../../event-bus.js';
    export default {
        data()
        {
            return{
                bankAcct:'',
                calendar:[],
                allData:[],
                months:[],
                years:[],
                dateMonth:'',
                dateYear:'',
                disableNext:false,
                disablePrev:false,
                lastYearOfSelectTag:'',
                showModal:false,
                dateSelected:'',
                datasOf:[],
                headerOf:[],
                bankName:'',

                tableShow:false,
                showMonthTable:false,
                monthOf: '',
                yearOf: '',

            }
        },
        mounted()
        {
            let bankacct = this.$route.params.bankacct;
            let bu       = this.$route.params.bu;
            axios.get('dtr/getbankAcct/'+bankacct).then(res => {
                this.bankAcct = res.data;
            });
            let $this  = this;
           // this.showMonthTable = true;
            axios.get('dtr/getBStable/'+bu+'/'+bankacct).then(res=>{
                this.showMonthTable = true;
                this.calendar  = res.data.calendar;
                this.months    = res.data.months;
                this.years     = res.data.Years;
                this.dateMonth = res.data.dateMonth;
                this.dateYear  = res.data.dateYear;
                this.monthOf   = this.dateMonth;
                this.yearOf    = this.dateYear;
//                console.log(this.dateYear);
                let years = "";
                let $this = this;
                Object.keys(this.years).forEach(function (key) {
                    years = $this.years[key];
                });
                this.lastYearOfSelectTag = years;

            });
           // console.log($this.yearOf);
//            this.monthOf = this.$refs.month.value;
//            this.yearOf  = this.$refs.year.value;

        },
        methods:{
            showTabular()
            {
              alert("dfafa");
            },
            getNextMonth()
            {
                let bankacct = this.$route.params.bankacct;
                let bu       = this.$route.params.bu;

                let month    = this.$refs.month.value;
                let year     = this.$refs.year.value;

                this.showMonthTable = true;
                if(month==11 && year == (new Date()).getFullYear())
                {
                    this.disableNext = true;
                }
                else
                {
                    this.disableNext = false;
                }

                let lastValue = this.lastYearOfSelectTag;

                if(month=='02' && year == lastValue)
                {
                    this.disablePrev = true;
                }
                else
                {
                    this.disablePrev = false;
                }

                let yearNow  = "";
                let monthNow = "";
                let nowMonths = month.replace(/^0+/, '').toString().replace(/(^\s*|\s*$)/g,'');
                nowMonths = parseInt(nowMonths)+1;
                if(nowMonths<=12)
                {
                    if(nowMonths <10)
                    {
                        nowMonths = "0"+nowMonths;
                    }
                    monthNow          = nowMonths;
                    yearNow           = year;
                    this.dateMonth = nowMonths;
                    this.dateYear  = year;

                    this.showMonthTable = true;
                    this.monthOf = nowMonths;
                    this.yearOf  = year;
                    EventBus.$emit('filter',[nowMonths,year]);
                }
                else
                {

                    nowMonths = '01';
                    if(nowMonths <10)
                    {
                        nowMonths = "0"+nowMonths;
                    }
                    year = parseInt(year)+1;
                    monthNow       = nowMonths;
                    yearNow        = year;
                    this.dateMonth = nowMonths;
                    this.dateYear  = year;

                    this.showMonthTable = true;
                    this.monthOf = nowMonths;
                    this.yearOf  = year;
                    EventBus.$emit('filter',[nowMonths,year]);
                }

                axios.get('dtr/calendar/'+bankacct+'/'+bu+'/'+yearNow+'/'+monthNow).then( res => {
                    this.calendar  = res.data.calendar;
//                    this.dateMonth = res.data.dateMonth;
//                    this.dateYear  = res.data.dateYear;
                })
            },
            getPrevMonth()
            {

                let bankacct = this.$route.params.bankacct;
                let bu       = this.$route.params.bu;

                let month    = this.$refs.month.value;
                let year     = this.$refs.year.value;

                this.showMonthTable = true;
                if(month==11 && year == (new Date()).getFullYear())
                {
                    this.disableNext = true;
                }
                else
                {
                    this.disableNext = false;
                }

                let lastValue = this.lastYearOfSelectTag;

                if(month=='02' && year == lastValue)
                {
                    this.disablePrev = true;
                }
                else
                {
                    this.disablePrev = false;
                }

                let yearNow  = "";
                let monthNow = "";
                let nowMonths = month.replace(/^0+/, '').toString().replace(/(^\s*|\s*$)/g,'');
                nowMonths = parseInt(nowMonths)-1;
                if(nowMonths!=0)
                {
                    if(nowMonths <10)
                    {
                        nowMonths = "0"+nowMonths;
                    }
                    monthNow          = nowMonths;
                    yearNow           = year;
                    this.dateMonth = nowMonths;
                    this.dateYear  = year;

                    this.showMonthTable = true;
                    this.monthOf = nowMonths;
                    this.yearOf  = year;
                    EventBus.$emit('filter',[nowMonths,year]);
                }
                else
                {

                    nowMonths = 12;
                    if(nowMonths <10)
                    {
                        nowMonths = "0"+nowMonths;
                    }
                    year = parseInt(year)-1;
                    monthNow       = nowMonths;
                    yearNow        = year;
                    this.dateMonth = nowMonths;
                    this.dateYear  = year;

                    this.showMonthTable = true;
                    this.monthOf = nowMonths;
                    this.yearOf  = year;

                    EventBus.$emit('filter',[nowMonths,year]);
                }
                axios.get('dtr/calendar/'+bankacct+'/'+bu+'/'+yearNow+'/'+monthNow).then( res => {
                    this.calendar  = res.data.calendar;
//                    this.dateMonth = res.data.dateMonth;
//                    this.dateYear  = res.data.dateYear;

                });



            },
            showRecords(e)
            {
                this.dateSelected = e.currentTarget.getAttribute('data-date');

                let bank     = this.$route.params.banks;
                let bankacct = this.$route.params.bankacct;
                let com      = this.$route.params.com;
                let bu       = this.$route.params.bu;

                this.bankName  = bank;

                this.datasOf   = [];
                let $this      = this;

                if(bank=='MB' || bank=="MBTC")
                {
                    this.headerOf = [
                        "Date",
                        "Check No.",
                        "Description",
                        "Debit",
                        "Credit",
                        "Balance",
                        "Branch"
                    ];
                    this.datasOf        = {};
                    $this.tableShow     = false;
                    axios.get('dtr/dataBankPerDate/'+bankacct+'/'+com+'/'+bu+'/'+this.dateSelected).then( res => {
                        this.datasOf    = res.data;
                        $this.tableShow = true;
                        $(document).ready(function () {
                            loadDataTable($("#bank-table"));
                        });
                    });
                }
                else if(bank=="BPI")
                {
                    this.headerOf = [
                        "Date",
                        "Check Number",
                        "SBA Reference No.",
                        "Branch",
                        "Transaction Code",
                        "Transaction Description",
                        "Debit",
                        "Credit",
                        "Running Balance"
                    ];
                    this.datasOf        = {};
                    $this.tableShow     = false;
                    axios.get('dtr/dataBankPerDate/'+bankacct+'/'+com+'/'+bu+'/'+this.dateSelected).then( res => {
                        this.datasOf    = res.data;
                        $this.tableShow = true;
                        $(document).ready(function () {
                            loadDataTable($("#bank-table"));
                        });
                    });
                }
                else if(bank=="LBP")
                {
                    this.headerOf = [
                        "Date",
                        "Description",
                        "Debit",
                        "Credit",
                        "Balance",
                        "Branch",
                        "Cheque Number"
                    ];
                    this.datasOf        = {};
                    $this.tableShow     = false;
                    axios.get('dtr/dataBankPerDate/'+bankacct+'/'+com+'/'+bu+'/'+this.dateSelected).then( res => {
                        this.datasOf    = res.data;
                        $this.tableShow = true;
                        $(document).ready(function () {
                            loadDataTable($("#bank-table"));
                        });
                    });
                }
                else if(bank=="PNB")
                {
                    this.headerOf = [
                        "Post Date",
                        "Value Date",
                        "NEG BR",
                        "Transaction Description",
                        "Check/ Seq No",
                        "Withdrawals",
                        "Deposits",
                        "Balance"
                    ];
                    this.datasOf        = {};
                    $this.tableShow     = false;
                    axios.get('dtr/dataBankPerDate/'+bankacct+'/'+com+'/'+bu+'/'+this.dateSelected).then( res => {
                        this.datasOf    = res.data;
                        $this.tableShow = true;
                        $(document).ready(function () {
                            loadDataTable($("#bank-table"));
                        });
                    });
                }
                else if(bank=="BDO")
                {
                    this.headerOf = [
                        "Posting Date",
                        "Branch",
                        "Description",
                        "Debit",
                        "Credit",
                        "Running Balance",
                        "Check Number"
                    ];
                    this.datasOf        = {};
                    $this.tableShow     = false;
                    axios.get('dtr/dataBankPerDate/'+bankacct+'/'+com+'/'+bu+'/'+this.dateSelected).then( res => {
                        this.datasOf    = res.data;
                        $this.tableShow = true;
                        $(document).ready(function () {
                            loadDataTable($("#bank-table"));
                        });
                    });
                }
                $this.showModal = true;
            },

            getProjects(e) {
//                axios.get(url, {params: this.tableData})
//                    .then(response => {
//                        this.projects = response.data;
//                        this.pagination.total = this.projects.length;
//                    })
//                    .catch(errors => {
//                        console.log(errors);
//                    });


            },

        },
        computed: {

        }
    }
</script>