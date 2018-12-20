<template>
    <div>
        <table class="table table-condensed table-hover">
            <thead>
                <tr>
                    <th v-for="(header, index) in header">{{header}}</th>
                </tr>

            </thead>
            <tbody>
                {{body.length}}
            <tr>
                <td v-html="button">

                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>

    export default {
        props:['header','body'],
        data()
        {
            return {
                date:'',
                button:''
            }
        },
        mounted()
        {
            var data = ["date='now'","bank='BPI'","@click='getAttrButton'"];
            var str = "";
            data.forEach(function(entry) {
                str +=" "+entry;
            });
            str = "<button "+str+" >Save</button>";
            this.button = str;
            console.log(str);
        },
        methods:{
            getAttrButton:function(e)
            {

                this.bank     = e.currentTarget.getAttribute('bank');
                console.log(this.bank);

                //console.log(e.currentTarget.getAttribute('data-bank'));
            },
        }

    }
</script>