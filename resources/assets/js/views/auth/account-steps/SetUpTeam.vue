<template>
    <div class="">
        <label for="email" class="label">Your team name</label>
        <p class="control">
            <input id="name" type="text" class="input" name="name" value="" v-model="team.name" required autofocus>
        <p class="help is-danger" v-text="getErrors('name')"></p>
        </p>
        <p class="control">
            <button class="button is-primary" @click="onSubmit">Submit</button>
        </p>
    </div>
</template>
<script>
    import store from '../../../store';
    import Team from '../../../core/Team'
    export default {
        data() {
            return{
                team: new Team({name:''})
            }
        },
        methods: {
            onSubmit() {
                this.$store.dispatch('SIGN_UP_SUBMIT',this.team);
            },
            /** method to get form field errors **/
            getErrors(fieldName) {
                return store.getters.getFormErrors(fieldName);
            }
        }
    }
</script>
