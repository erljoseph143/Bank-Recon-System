<template>
    <div class="form-me">
        <div v-show="formShow" class="row col-md-12">
            <form @submit="uploadFile" action="" refs="myform" id="dtr-upload" enctype="multipart/form-data">
                <input type="hidden" name="_token" :value="csrf">
                <div class="col-md-12 margin-top-10">
                    <div class="input-group full-width">
                <span class="input-group-btn span-width">
                    <button class="btn form-btn" type="button">Bank Account</button>
                </span>
                        <div class="full-width">
                            <div class="form-control data-for">{{ bankAcct }}</div>
                            <input type="hidden" name="bankAcct" ref="bankAcct" class="bankAcct" id="bankAcct" value="">
                            <input type="hidden" name="com" ref="com"  class="com" id="com" value="">
                            <input type="hidden" name="bu" ref="bu" class="bu" id="bu" value="">
                        </div>
                    </div>
                </div>

                <div class="col-md-12 margin-top-10 bpi-type" v-bind:class="{ 'hidden': bpitype}">
                    <div class="input-group full-width">
                <span class="input-group-btn span-width">
                    <button class="btn form-btn" type="button">BPI TYPE</button>
                </span>
                        <div class="full-width">
                            <select name="bpiType" id="bpi-type" ref="bpiType" class="form-control" v-on:change="selectChange">
                                <option value="">-------------------Select BPI type---------------------</option>
                                <option value="BIZLINK">BPI-BIZLINK</option>
                                <option value="EXPLINK">BPI-EXPLINK</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 margin-top-10 bank-year " v-bind:class="{'hidden': express}">
                    <div class="input-group full-width">
                <span class="input-group-btn span-width">
                    <button class="btn form-btn" type="button">Year</button>
                </span>
                        <div class="full-width">
                            <select name="year" id="year" ref="year" class="form-control" v-on:change="selectChange">
                                <option value="">-------------------Select Bank Year---------------------</option>
                                <option v-for="(yr,index) in year" :value="yr">{{yr}}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12 margin-top-10">
                        <label for="file" class="btn btn-default fileinput-button">
                            <input id="file" type="file" ref="dtr" name="dtr" >
                        </label>
                        <label for="" class="num-file"></label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12 margin-top-10">
                        <button type="submit" class="btn btn-default" >
                            <i class="fa fa-cloud-upload"></i>
                            Upload
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="row">
            <div v-if="errorBalance" class="col-md-4">
                <not-equal :errorsData="ErrorMessage">{{ErrorMessage}}</not-equal>
            </div>
            <div v-if="invalidError" class="col-md-12 alert alert-danger">
                <p style="color:red">Error in Uploading</p>
                {{ErrorMessage}}
            </div>

            <modal v-model="openModal" title="Modal Title" size="lg modal-full" :backdrop="false">
                <error-list :errorList="ErrorMessage" ></error-list>
            </modal>

            <div v-if="progressBar"  class="progress-me col-md-12">
                <div class="row">
                    <div class="col-md-3 col-md-offset-4">
                        <div id="percent-data" style="font-size:35px;width: 50px;height: 50px;padding:10px;">

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="progress progress-striped active">
                            <div class="progress-bar progress-bar-danger" :style="'width:'+percent+'%;'" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">
                               <div style="position:relative;width:inherit;height: inherit;">{{percent}} %</div>
                                <span class="sr-only">
                                    80% Complete (danger)
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                formShow:true,
                bpitype:true,
                express:true,
                bankAcct:'',
                year:[],
                form: new FormData,
                file:null,
                ErrorMessage:'',
                errorBalance:false,
                invalidError:false,
                openModal:false,
                progressBar:false,
                percent:0,
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        },
        mounted()
        {
            let bank                  = this.$route.params.banks;
            let bankId                = this.$route.params.bankacct;
            this.$refs.bankAcct.value = bankId;
            this.$refs.com.value      = this.$route.params.com;
            this.$refs.bu.value       = this.$route.params.bu;
            if(bank.trim()=='BPI')
            {
                this.bpitype=false;
            }
            axios.get('dtr/getbankAcct/'+bankId).then(res => {
                this.bankAcct = res.data;
            });
            axios.get('dtr/getListYear').then(res => {
                let data  = [];
                for(let yr in res.data)
                {
                    data.push(yr);
                }
                this.year = data.reverse();
            });
        },
        methods:
            {
                selectChange()
                {
                   if(this.$refs.bpiType.value == "EXPLINK")
                   {
                        this.express = false;
                   }
                   else
                   {
                       this.express = true;
                   }
                },
                uploadFile(e)
                {
                    e.preventDefault();
                    this.file     = this.$refs.dtr.files[0];
                    this.percent  = 0;
                     let $this    = this;
                     let form     = document.getElementById('dtr-upload');
                     let formData = new FormData(form);
                     let file     = this.$refs.dtr.files[0];
                     let bankAcct = this.$refs.bankAcct.value;
                     let com      = this.$refs.com.value;
                     let bu       = this.$refs.bu.value;
                     let xhr      = new XMLHttpRequest();

                     let errorVar = "";
                     xhr.onreadystatechange = function(vm) {
                        if(this.readyState == 3)
                        {
                            let result = JSON.parse(this.responseText.match(/{[^}]+}$/gi));

                            errorVar = result.error;
                            if(result.error!=null)
                            {
                                if(result.error.replace(/(^\s*|\s*$)/g,'') == "Not balance")
                                {
                                    xhr.abort();
                                    $this.errorBalance    = true;
                                    $this.invalidError    = false;
                                    axios.get('dtr/errorbalance/'+result.messageError).then(res => {
                                        $this.ErrorMessage = res.data;
                                    });
                                    return false;
                                }
                                else if(result.error.replace(/(^\s*|\s*$)/g,'')=="Invalid format")
                                {
                                    xhr.abort();
                                    axios.get('dtr/invalid/'+result.messageError).then(res => {
                                        $this.ErrorMessage = res.data;
                                    });
                                    $this.invalidError = true;
                                    $this.errorBalance = false;
                                   return false;
                                }
                                else if(result.error.replace(/(^\s*|\s*$)/g,'') == "Data Error")
                                {
                                    xhr.abort();
                                    axios.post('dtr/showErrors',{errorArray:result.messageError}).then(res=>{
                                        $this.ErrorMessage = res.data;
                                    });
                                    $this.openModal = true;
                                    return false;
                                }
                            }
                            $this.formShow     = false;
                            $this.invalidError = false;
                            $this.errorBalance = false;
                            $this.progressBar  = true;
                            if(Math.round(result.pecent)==99)
                            {
                                $this.percent = 100;
                            }
                            else
                            {
                                $this.percent = result.percent.toFixed(2);
                            }
                        }
                        else if(this.readyState == 4)
                        {
                            $this.percent = 100;
                           // window.location = "http://localhost:8080/brsVue/public/dtr/home#/banks/LBP";
                            let bankOf = $this.$route.params.banks;
                            if(errorVar==null)
                            {
                                setTimeout(function(){
                                    $this.$router.push("/banks/"+bankOf);
                                },2000);
                            }


                        }
                    }.bind(xhr, this);
                    xhr.open('POST', $baseUrl+'/dtr/DTRsaving');
                    xhr.send(formData);
                }
            }

    }
</script>

<style>
    .form-btn
    {
        background: #faebcc;
        color: green;
        border-bottom: 3px solid green;
        width: 100%;
    }
    .span-width
    {
        width: 150px;
    }

    .full-width
    {
        width: 100%;
    }
</style>