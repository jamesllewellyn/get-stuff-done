<template>
 <div class="box column is-one-third">
     <h3 class="has-text-centered">{{name}}<a  @click.prevent.stop="toggelModal('addTask')"><i class="fa fa-plus-circle is-pulled-right align-vertical" aria-hidden="true"></i></a></h3>
 </div>
</template>

<script>
    export default {
        props: {
            name: {
                type: String,
                required: true
            },
            tasks: []
        },
        methods:{
            toggelModal: function(modalName){
                Event.$emit(modalName);
            }
       },
        mounted() {
        }
    }
</script>
