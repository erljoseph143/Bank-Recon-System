<template>
    <div>
        <h2>Users List</h2>
        <a href="users/create" class="btn btn-success pull-right">Add New User</a>
        <table class="table table-bordered">
            <thead>
            <tr>
                <td>ID</td>
                <td>Image</td>
                <td>Email</td>
                <td>Action</td>
            </tr>
            </thead>
            <tbody>
                <tr v-for="(user, index) in users">
                    <td>{{user.user_id}}</td>
                    <td>{{user.firstname}}</td>
                    <td>{{user.email}}</td>
                    <td>
                        <a :href="'users/'+user.user_id+'/edit'" class="btn btn-primary">Edit</a>
                        <a href="javascript:;" class="btn btn-danger" v-on:click="deleteUser(user.user_id,index)">Delete</a>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="paginate">
            <button class="btn btn-default" v-on:click="fetchPaginateUsers(pagination.prev_page_url)" :disabled="!pagination.prev_page_url">
                Previous
            </button>
            <span>
                Page {{pagination.current_page}} of {{pagination.last_page}}
            </span>
            <button class="btn btn-default" v-on:click="fetchPaginateUsers(pagination.next_page_url)" :disabled="!pagination.next_page_url">
                Next
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
          return {
              users:[],
              url:'api/users',
                pagination:[]
//              user:{
//                  user_id:0,
//                  firstname:'',
//                  lastname:'',
//                  email:''
//              }
          }
        },
        mounted()
        {
            this.getUsers()
        },
        methods:{
            getUsers()
            {
                let $this = this;
                axios.get(this.url).then(response => {
                    this.users = response.data.data;
                    $this.makePagination(response.data)
//                    console.log(response)
//                   this.users = response
                    //console.log(this.users.id);
                });
            },
            deleteUser(id,index){
                axios.delete('api/users/'+id).then(response =>{
                    console.log(response)
                    this.users.splice(index,1);
                }).catch(error => {
                    console.log(error)
                })
            },
            makePagination(data)
            {
                let pagination = {
                    current_page  : data.current_page,
                    last_page     : data.last_page,
                    next_page_url : data.next_page_url,
                    prev_page_url : data.prev_page_url
                }
                this.pagination = pagination;
            },
            fetchPaginateUsers(url)
            {
                this.url = url;
                this.getUsers();
            }
        }

    }
</script>

<style>

</style>