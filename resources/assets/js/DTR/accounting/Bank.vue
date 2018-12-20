<template>
    <div>
        <!--<transition name="slide" enter-active-class="animated fadeIn" leave-active-class="animated slideOutLeft">-->
            <!--<div v-if="show" v-bind:class="{ 'hidden': !isActive}" class="row all-banks col-md-offset-2" style="margin-top: 8%;margin-bottom: 8%;">-->
                <!--<router-link :to="'/banks/LBP'" >-->
                    <!--<div class="col-sm-12 col-md-2 banks" v-on:click="getBank"  data-bank="LBP">-->
                        <!--<div class="thumbnail" style="height: 9em;border: 1px solid #ddd;background-color: #fafafa;">-->
                            <!--<img :src="base_url+'/banklogo/Land Bank.png'" alt="" style="width: 100px;height: 70px;margin-top: 12px;">-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</router-link>-->
                <!--<router-link :to="'/banks/BPI'" >-->
                    <!--<div class="col-sm-12 col-md-2 banks"  v-on:click="getBank" data-bank="BPI">-->
                        <!--<div class="thumbnail" style="height: 9em;border: 1px solid #ddd;background-color: #fafafa;">-->
                            <!--<img :src="base_url+'/banklogo/BPI.png'" alt="" style="width: 90px;height: 55px;margin-top: 24px;">-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</router-link>-->
                <!--<router-link :to="'/banks/MB'" >-->
                    <!--<div class="col-sm-12 col-md-2 banks"  v-on:click="getBank" data-bank="MB">-->
                        <!--<div class="thumbnail" style="height: 9em;border: 1px solid #ddd;background-color: #fafafa;">-->
                            <!--<img :src="base_url+'/banklogo/Metro Bank.png'" alt="" style="width: 90px;height: 90px;margin-top: 6px;">-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</router-link>-->
                <!--<router-link :to="'/banks/PNB'" >-->
                    <!--<div class="col-sm-12 col-md-2 banks"  v-on:click="getBank" data-bank="PNB">-->

                        <!--<div class="thumbnail" style="height: 9em;border: 1px solid #ddd;background-color: #fafafa;">-->
                            <!--<img :src="base_url+'/banklogo/PNB.png'" alt="" style="width: 90px;height: 90px;margin-top: 10px;">-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</router-link>-->
            <!--</div>-->
        <!--</transition>-->
        <!--<transition enter-active-class="animated fadeIn">-->
            <!--<div v-if="!show" class="row margin-top-10 data-bank"  v-bind:class="{ 'hidden': isActive}">-->
                <!--<button class="btn btn-default show-bank " @click="showbanks" style="margin-left:15px;">-->
                    <!--<i class="glyphicon glyphicon-arrow-left"></i>-->
                    <!--Show all banks-->
                <!--</button>-->
                <!--<div class="col-md-12 form-group margin-top-10" id="bs-acct-table">-->
                    <!--<router-view></router-view>-->
                <!--</div>-->
            <!--</div>-->
        <!--</transition>-->

        <ul class="nav nav-tabs">
            <li :class="LBPstatus">
                <router-link :to="'/accounting/banks/LBP'" exact  >
                    <img :src="base_url+'/banklogo/Land Bank.png'" alt="" v-on:click="getBank" data-bank="LBP" style="width: 80px;height: 50px;">
                </router-link>
            </li>
            <li :class="BPIstatus">
                <router-link :to="'/accounting/banks/BPI'" exact >
                    <img :src="base_url+'/banklogo/BPI.png'" alt="" v-on:click="getBank" data-bank="BPI" style="width: 80px;height: 50px;">
                </router-link>
            </li>
            <li :class="MBstatus">
                <router-link :to="'/accounting/banks/MB'" exact >
                    <img :src="base_url+'/banklogo/Metro Bank.png'" alt="" v-on:click="getBank" data-bank="MB" style="width: 60px;height: 50px;">
                </router-link>
            </li>
            <li :class="PNBstatus">
                <router-link :to="'/accounting/banks/PNB'" exact>
                    <img :src="base_url+'/banklogo/PNB.png'" alt="" v-on:click="getBank" data-bank="PNB" style="width: 60px;height: 50px;">
                </router-link>
            </li>
            <li :class="BDOstatus">
                <router-link :to="'/accounting/banks/BDO'" exact>
                    <img :src="base_url+'/banklogo/BDO.png'" alt="" v-on:click="getBank" data-bank="BDO" style="width: 80px;height: 40px; margin-top:5px;margin-bottom:5px">
                </router-link>
            </li>
        </ul>
        <div class="tab-content">
            <div class="" style="height: 38em;overflow-y:auto">
                <router-view></router-view>
            </div>


        </div>
    </div>
</template>

<script>
    export default {
        props:['databank'],
        data()
        {
           // LBP:"LBP"
          return {
              base_url:baseUrl,
              bank:'',
              isActive:true,
              message:'Try sliding',
              show:true,
              LBPstatus:'',
              BPIstatus:'',
              MBstatus:'',
              PNBstatus:'',
              BDOstatus:''
          }
        },
        mounted()
        {
            //this.getBank()
        },
        methods:{
            LBP()
            {
                console.log("dfsdfsd")
            },
            getBank:function(e)
            {
                this.isActive = false;
                this.bank     = e.currentTarget.getAttribute('data-bank');
                this.show     = !this.show;
                if(this.bank == "LBP")
                {
                    this.LBPstatus= 'active';
                    this.BPIstatus= '';
                    this.MBstatus = '';
                    this.PNBstatus= '';
                    this.BDOstatus= '';
                }
                else if(this.bank == "BPI")
                {
                    this.LBPstatus= '';
                    this.BPIstatus= 'active';
                    this.MBstatus = '';
                    this.PNBstatus= '';
                    this.BDOstatus= '';
                }
                else if(this.bank == "MB")
                {
                    this.LBPstatus= '';
                    this.BPIstatus= '';
                    this.MBstatus = 'active';
                    this.PNBstatus= '';
                    this.BDOstatus= '';
                }
                else if(this.bank == "PNB")
                {
                    this.LBPstatus= '';
                    this.BPIstatus= '';
                    this.MBstatus = '';
                    this.PNBstatus= 'active';
                    this.BDOstatus= '';
                }
                else if(this.bank == "BDO")
                {
                    this.LBPstatus= '';
                    this.BPIstatus= '';
                    this.MBstatus = '';
                    this.PNBstatus= '';
                    this.BDOstatus= 'active';
                }
                //console.log(e.currentTarget.getAttribute('data-bank'));
            },
            showbanks()
            {
                this.isActive = true;
                this.show = !this.show
            }
        }

    }
</script>ss

<style>
    /*@import "https://cdn.jsdelivr.net/npm/animate.css@3.5.1";*/

    .banks:hover
    {
        cursor: pointer;
        background: #ff8100;
    }

    /*.slide-leave-active,*/
    /*.slide-enter-active {*/
        /*transition: 0.2s;*/
        /*transition-delay: 1s;*/
    /*}*/
    /*.slide-enter {*/
        /*transform: translate(100%, 0);*/
    /*}*/
    /*.slide-leave-to {*/
        /*transform: translate(-100%, 0);*/
    /*}*/



</style>