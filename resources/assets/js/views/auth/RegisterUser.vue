<template>
    <div class="column is-5 is-centered">
        <div class="">
            <div class="content">
                <h2 >
                    Create New User
                </h2>
                <p class="control">Before we can create you a team we need to create you a user account.</p>
            </div>
            <div class="box">
                <form role="form" @submit.prevent>
                    <label for="first_name" class="label">First name</label>
                    <p class="control">
                        <input id="first_name" type="text" class="input" name="first_name" v-model="user.first_name" autofocus>
                    </p>
                    <p class="help is-danger" v-text="getErrors('first_name')"></p>
                    <label for="last_name" class="label">Last name</label>
                    <p class="control">
                        <input id="last_name" type="text" class="input" name="last_name" v-model="user.last_name">
                    </p>
                    <p class="help is-danger" v-text="getErrors('email')"></p>
                    <label for="email" class="label">Email</label>
                    <p class="control">
                        <input id="email" type="email" class="input" name="email" v-model="user.email">
                    </p>
                    <p class="help is-danger" v-text="getErrors('last_name')"></p>
                    <label for="handle" class="label">Handle</label>
                    <p class="control">
                        <input id="handle" type="text" class="input" name="handle" value="" v-model="user.handle">
                    </p>
                    <p class="help is-danger" v-text="getErrors('handle')"></p>
                    <p><small>Your handle will be displayed along with your messages in Get Stuff Done.</small></p>
                    <label for="password" class="label">Password</label>
                    <p class="control">
                        <input id="password" type="password" class="input" name="password" value="" v-model="user.password" >
                    </p>
                    <p class="help is-danger" v-text="getErrors('password')"></p>
                    <p class="control">
                        <button class="button is-primary" @click="onSubmit">Submit</button>
                    </p>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import store from '../../store';
    import { mapState } from 'vuex'

    export default {
        data() {
            return{

            }
        },
        components: {

        },
        computed:
            mapState([
                'user'
            ])
        ,
        methods: {
            /** trigger toggle modal event */
            triggerEvent: function(eventName, payload){
                Event.$emit(eventName, payload);
            },
            onSubmit() {
                this.$store.dispatch('REGISTER_USER', this.user);
            },
            /** method to get form field errors **/
            getErrors(fieldName) {
                return store.getters.getFormErrors(fieldName);
            }
        },
        mounted() {
        }
    }
</script>
