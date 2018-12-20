<template>
    <div>
        <h2>Edit User</h2>
        <form action="api/users" method="post" class="horizontal">
            <div class="form-group">
                <label for="firstname" class="control-label col-sm-2">First Name:</label>
                <div class="col-sm-10">
                    <input type="text" name="firstname" placeholder="Entrer First Name" class="form-control" id="firstname" v-model="firstname">
                    <!--<span class="error text-danger" v-if="errors.firstname">{{errors.firstname[0]}}</span>-->
                </div>
            </div>
            <div class="form-group">
                <label for="lastname" class="control-label col-sm-2">Last Name:</label>
                <div class="col-sm-10">
                    <input type="text" name="lastname" placeholder="Entrer Last Name" class="form-control" id="lastname" v-model="lastname">
                    <!--<span class="error text-danger" v-if="errors.lastname">{{errors.lastname[0]}}</span>-->
                </div>
            </div>
            <div class="form-group">
                <label for="lastname" class="control-label col-sm-2">Email:</label>
                <div class="col-sm-10">
                    <input type="email" name="email" placeholder="Entrer email" class="form-control" id="email" v-model="email">
                    <!--<span class="error text-danger" v-if="errors.email">{{errors.email[0]}}</span>-->
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="button" class="btn btn-default" v-on:click="UpdateUser()"> Update</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        props:['id'],
        data()
        {
            return {
                firstname:'',
                lastname:'',
                email:''
            }
        },
        mounted()
        {
            axios.get('api/users/'+this.id).then(response => {
              //  console.log(response.data)
                this.firstname =  response.data.firstname;
                this.lastname  =  response.data.lastname;
                this.email     =  response.data.email;
            }).catch(error => {
               // console.log(error.response.data.errors)
            })
        },
        methods:
        {
            UpdateUser(){
                axios.put('api/users/'+this.id,{
                    firstname:this.firstname,
                    lastname:this.lastname,
                    email:this.email
                }).then(response => {
                    console.log(response.data)
                }).catch(error => {
                    console.log(error.response.data)
                })
            }

        }
    }
</script>