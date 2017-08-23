<template>
    <div class="field">
        <label class="label">Select Member</label>
        <div class="control">
            <div class="cs-select cs-skin-rotate">
                <span class="cs-placeholder fake-select">Choose your option</span>
                <div class="cs-options">
                    <ul class="user-select">
                        <li class="user-select-item" v-for="user in users" :key="user.id" @click="changed(user.id)">
                            <div class="columns">
                                <div class="col-xs-2">
                                    <img :src="user.avatar_url" alt="" class="circle">
                                </div>
                                <div class="column has-text-centered">
                                    <p v-text="user.first_name"></p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <!--<select class="cs-select is-hidden" v-model="user">-->
                    <!--<option v-for="user in users" v-text="user.handle" :key="user.id" value="user.id"></option>-->
                <!--</select>-->
            </div>
        </div>
    </div>
</template>

<script>
    import store from '../store';
    export default {
        data() {
        return{
                user: '',

            }
        },
        computed: {
            users:function() {
                return store.getters.getTeamUser
            }
        },
        methods: {
            triggerEvent: function(userId){
                this.user = userId;
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
