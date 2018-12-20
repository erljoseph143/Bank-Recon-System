<template>
    <div>
        <p style="color:red">Error in Uploading</p>
        <p>
            <button @click="showError" class="btn btn-danger">Show Error Details</button>
        </p>
        <div v-if="errorTable" class="alert alert-danger">
            <table  class="table table-bordered table-striped">
                <tr>
                    <td>Current Balance Uploaded</td>
                    <td></td>
                    <td style="text-align:right">{{currentbal.toString().replace(/\B(?=(\d{3})+(?!\d))/g,',')}}</td>
                </tr>
                <tr>
                    <td>Debit</td>
                    <td style="text-align:center"> - </td>
                    <td style="text-align:right">{{debit=0?debit.toString().replace(/\B(?=(\d{3})+(?!\d))/g,','):''}}</td>
                </tr>
                <tr>
                    <td>Credit</td>
                    <td style="text-align:center"> + </td>
                    <td style="text-align:right">{{credit!=0 ? credit.toString().replace(/\B(?=(\d{3})+(?!\d))/g,',') :''}}</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td></td>
                    <td style="text-align:right">{{ Math.round(total,2).toString().replace(/\B(?=(\d{3})+(?!\d))/g,',') }}</td>
                </tr>
                <tr>
                    <td>Your file running Balance</td>
                    <td style="text-align:center"> - </td>
                    <td style="text-align:right">{{balance.toString().replace(/\B(?=(\d{3})+(?!\d))/g,',')}}</td>
                </tr>
                <tr>
                    <td>Balance Not Equal</td>
                    <td></td>
                    <td style="text-align:right">{{Math.round(totalbal,2).toString().replace(/\B(?=(\d{3})+(?!\d))/g,',')}}</td>
                </tr>
            </table>
        </div>

    </div>
</template>

<script>

    export default {
        props:['errorsData'],
        data(){
            return {
                list:'',
                debit:0,
                credit:0,
                balance:0,
                currentbal:0,
                total:0,
                errorTable:false,
                totalbal:0
            }
        },
        mounted()
        {
           // console.log(this.dataArray);
           //this.$refs.dataError.value = " fsd";
          //  alert(this.errorsData);
        },
        methods:
            {
                showError()
                {
                    this.errorTable = true;
//                    debit:0,
//                        credit:0,
//                    balance:0,
//                    currentbal:0,
                    let data = this.errorsData;
                    let str  = "";
                    data.forEach(function(entry) {
                        str +=" "+entry;
                    });
                   this.debit      = parseFloat(data[0]);
                   this.credit     = parseFloat(data[1]);
                   this.balance    = parseFloat(data[2]);
                   this.currentbal = parseFloat(data[3]);

                   if(this.debit == 0 || isNaN(this.debit))
                   {
                       this.total = this.currentbal + this.credit;
                   }
                   else
                   {
                       this.total = this.currentbal - this.debit;
                   }

                   this.totalbal = this.total - this.balance;

                }
            },
        created() {
           // alert(this.errorsData);

         //  console.log(this.$refs.dataError.value);
        },

    }
</script>