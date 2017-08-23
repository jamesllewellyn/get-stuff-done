<template>
    <div class="column is-4 is-offset-4">
        <h1 class="title">
            Create New Team
        </h1>
        <div class="box">
            <form role="form" @submit.prevent>
                <transition name="fade" mode="out-in">
                    <router-view  transition="fade" transition-mode="out-in" class="animated"></router-view>
                </transition>
            </form>
        </div>
    </div>
</template>

<script>
    import store from '../../store';

    export default {
        data() {
            return{

            }
        },
        components: {
        },
        computed: {
        },
        methods: {
            /** trigger toggle modal event */
            triggerEvent: function(eventName, payload){
                Event.$emit(eventName, payload);
            }
        },
        mounted() {
        }
    }
</script>
