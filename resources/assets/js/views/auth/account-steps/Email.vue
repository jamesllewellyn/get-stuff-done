<template>
    <div class="">
        <label for="email" class="label">Your email address</label>
        <p class="control">
            <input id="email" type="text" class="input" name="email" value="" v-model="user.email" autofocus>
            <p class="help is-danger" v-text="getErrors('email')"></p>
        </p>
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
                this.$store.dispatch('CHECK_IF_USER', this.user);
            },
            /** method to get form field errors **/
            getErrors(fieldName) {
                return store.getters.getFormErrors(fieldName);
            }
        }
    }
</script>
