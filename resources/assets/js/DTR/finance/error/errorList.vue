<template>
    <div>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Filename</th>
                <th>Error Description</th>
                <th>Column</th>
                <th>Row</th>
                <th>Value</th>
            </tr>
            </thead>
            <tbody>
            <!--@foreach($errorArray as $key => $error)-->
            <!--@if(count($error)>0)-->
            <tr v-for="(errors,index) in errorList">
                <td>
                    <!--loadError('{{$error[0]."|".$error[2]."|".$error[3]}}')-->
                    <a href="#" @click="errorData" :filename="errors[0]" :column="errors[2]" :row="errors[3]" >
                    {{errors[0]}}
                    </a>
                </td>
                <td>{{errors[1]}}</td>
                <td>{{errors[2]}}</td>
                <td>{{errors[3]}}</td>
                <td>{{errors[4]}}</td>
            </tr>
            <!--@endif-->

            <!--@endforeach-->
            </tbody>
        </table>

        <modal v-model="openModalFile" append-to-body title="Modal Title" size="lg modal-full">
            <div v-html="data"></div>
        </modal>
    </div>
</template>

<script>
    export default {
        props:['errorList'],
        data(){
            return {
                data:'',
                openModalFile:false
            }
        },
        methods:
            {
                errorData(e)
                {
                    let $this  = this;
                    let file   = e.currentTarget.getAttribute('filename');
                    let column = e.currentTarget.getAttribute('column');
                    let row    = e.currentTarget.getAttribute('row');
//                    url:'{{url('read_bank_error')}}/'+filename+"/Error/"+row,
                    axios.get('api/dtr/read_bank_error/'+file+'/Error/'+row).then( res => {
                        $this.data = res.data;
                    });
                    this.openModalFile = true;

                }
            }

    }
</script>