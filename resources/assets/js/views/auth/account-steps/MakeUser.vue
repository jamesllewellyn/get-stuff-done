<template>
    <div class="">
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
        <label for="password" class="label">Password</label>
        <p class="control">
            <input id="password" type="password" class="input" name="password" value="" v-model="user.password" >
        </p>
        <p class="help is-danger" v-text="getErrors('password')"></p>
        <p class="control">
            <button class="button is-primary" @click="onSubmit">Submit</button>
        </p>
    </div>
</template>
<script>
    import store from '../../../store';
    import { mapState } from 'vuex'
    export default {
        computed:
            mapState([
                'user'
            ])
        ,
        methods: {
            onSubmit() {
                this.$store.dispatch('REGISTER_USER', this.user);
            },
            /** method to get form field errors **/
            getErrors(fieldName) {
                return store.getters.getFormErrors(fieldName);
            }
        }
    }
</script>
