<template>
    <tr>
        <td>
            <i class="fa fa-sort" aria-hidden="true"></i>
            <a href="#"><i class="fa fa-check-circle-o" aria-hidden="true"></i> </a>
        </td>
        <td class="is-centered-text">{{name}}</td>
        <td class="is-centered-text">{{priority}}</td>
        <td class="is-centered-text">{{due_date}}</td>

    </tr>
</template>

<script>
    export default {
        props:{
            name:{
             type: String,
             required: true
            },
            priority_id:{
                type: Number,
                required: true
            },
            due_date:{
                type: String,
                required: true
            },
            id:{
                type: Number,
                required: true
            }
        },
        computed:{
            priority : function () {
                switch (this.priority_id){
                    case 1 :
                        return 'High';
                    break;
                    case 2 :
                        return 'Medium';
                    case 3:
                        return 'Low';
                }
            }
        },
        mounted() {

        }
    }
</script>
