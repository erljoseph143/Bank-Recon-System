<template>
    <div>
        <div v-if="tableShow">
            <table id="dtr" class="table table-condensed table-hover">
                <thead>
                <tr>
                    <td>Bank</td>
                    <td>Account No.</td>
                    <td>Account Name</td>
                    <td>Business Unit</td>
                    <td>Lastest Date Uploaded</td>
                    <td>Current Balance</td>
                    <td>Action</td>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(banks, index) in allbank[0]">
                    <td>{{banks.bank}}</td>
                    <td>{{banks.accountno}}</td>
                    <td>{{banks.accountname}}</td>
                    <td>{{banks.businessunit.bname}}</td>
                    <td>{{allbank[1][index].date}}</td>
                    <td>{{allbank[1][index].amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g,',')}}</td>
                    <td>
                        <router-link :to="'/dtr/form/'+banks.bank+'/'+banks.id+'/'+banks.company_code+'/'+banks.buid" class="btn btn-default upload-bank">
                            <i class="glyphicon glyphicon-upload"></i>
                        </router-link>
                        <router-link :to="'/dtr/view/'+banks.bank+'/'+banks.id+'/'+banks.company_code+'/'+banks.buid" class="btn btn-default">
                            <i class="glyphicon glyphicon-eye-open"></i>
                        </router-link>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!--data-company=""-->
        <!--data-bu=""-->
        <!--data-bank-acct=""-->
        <!--data-bank=""-->
        <!--data-for=""-->
        <!--data-bankid=""-->
        <!--v-bind:data-company="banks.company_code"-->
        <!--v-bind:data-bu="banks.buid"-->
        <!--v-bind:data-bank-acct="banks.id"-->
        <!--v-bind:data-bank="banks.bank"-->
        <!--v-bind:data-for="banks.bank+' - '+banks.accountno+' - '+banks.accountname"-->
        <!--v-bind:data-bankid="banks.id"-->
        <section>
            <!--<btn type="primary" @click="open1=true">Large Modal</btn>-->
            <!--<btn type="primary" @click="open2=true">Small Modal</btn>-->
            <!--<modal v-model="open1" title="Modal Title" size="lg">-->
                <!--&lt;!&ndash;<p>{{dame}}</p>&ndash;&gt;-->
                <!--<modal-table :header="theader" :body="tbody"></modal-table>-->
            <!--</modal>-->
            <!--<modal v-model="open2" title="Modal Title" size="sm">-->

            <!--</modal>-->
        </section>
    </div>
</template>

<script>
    export default {
        data()
        {
            return {
                bank:'',
                allbank:[],
                open1: false,
                open2: false,
                theader:[],
                tbody:[],
                role:'Admin',
                tableShow:false

            }

        },
        mounted(){
            this.tableShow = true
            this.bank = this.$route.params.bank;
            axios.get('dtr/allbanks/'+this.bank).then(res => {
                this.allbank = res.data;
            }).catch(error =>{
                console.log(error.response.data);
            });

            $(document).ready(function(){
                setTimeout(function(){
                    $("#dtr").DataTable();
                },1000);

            });
        },
        methods:
            {
                modalShow()
                {
                    this.open1 = true;
                    //this.dame ="Ok kaau";
                  var tableHeader = ['Bank Date','Description','Check No','Debit','Credit','Balance'];
                    this.theader = tableHeader;
                    this.tbody = this.allbank;
                }
            },
        beforeRouteEnter (to, from, next) {
            next(vm => {
                // put your logic here
//                if (vm.role === 'Admin') {
////                    vm.bank = vm.$route.params.bank;
////                    axios.get('dtr/allbanks/'+vm.bank).then(res => {
////                        vm.allbank = res.data;
////                    }).catch(error =>{
////                        console.log(error.response.data);
////                    });
////                    alert("alallala");
//                   // vm.role = vm.$route.params.bank;
//                }
            })
        },
        // when route changes and this component is already rendered,
        // the logic will be slightly different.
        watch: {
            $route () {
                this.tableShow = false;
                this.bank = this.$route.params.bank;
                axios.get('dtr/allbanks/'+this.bank).then(res => {
                    this.tableShow = true;
                    this.allbank = res.data;


                }).catch(error =>{
                    console.log(error.response.data);
                });

                $(document).ready(function(){
                    setTimeout(function(){
                        $("#dtr").DataTable();
                    },500);

                });
            }
        },
        ready()
        {

        }

    }

</script>