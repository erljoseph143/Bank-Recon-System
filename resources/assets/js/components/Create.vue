<template>
    <div>
        <form action="api/users" method="post" class="horizontal">
            <div class="form-group">
                <label for="firstname" class="control-label col-sm-2">First Name:</label>
                <div class="col-sm-10">
                    <input type="text" name="firstname" placeholder="Entrer First Name" class="form-control" id="firstname" v-model="firstname">
                    <span class="error text-danger" v-if="errors.firstname">{{errors.firstname[0]}}</span>
                </div>
            </div>
            <div class="form-group">
                <label for="lastname" class="control-label col-sm-2">Last Name:</label>
                <div class="col-sm-10">
                    <input type="text" name="lastname" placeholder="Entrer Last Name" class="form-control" id="lastname" v-model="lastname">
                    <span class="error text-danger" v-if="errors.lastname">{{errors.lastname[0]}}</span>
                </div>
            </div>
            <div class="form-group">
                <label for="lastname" class="control-label col-sm-2">Email:</label>
                <div class="col-sm-10">
                    <input type="email" name="email" placeholder="Entrer email" class="form-control" id="email" v-model="email">
                    <span class="error text-danger" v-if="errors.email">{{errors.email[0]}}</span>
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="control-label col-sm-2">Password:</label>
                <div class="col-sm-10">
                    <input type="password" name="password" placeholder="Entrer password" class="form-control" id="password" v-model="password">
                    <span class="error text-danger" v-if="errors.password">{{errors.password[0]}}</span>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="button" class="btn btn-default" v-on:click="addNewUser()"> Save</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default{
        data()
        {
            return {
                firstname:'',
                lastname:'',
                email:'',
                password:'',
                errors:[]
            }
        },
        methods:{
            addNewUser(){
                console.log(this.firstname);

                axios.post('api/users',{
                    firstname:this.firstname,
                    lastname:this.lastname,
                    email:this.email,
                    password:this.password
                }).then(response => {
                    console.log(response)
                    this.firstname ='';
                    this.lastname ='';
                    this.email ='';
                    this.password ='';
                }).catch(error => {
                    console.log(error)
                    if(error.response.status == 422)
                    {
                        this.errors = error.response.data.errors;
                    }
                })
            }
        }
    }
</script>