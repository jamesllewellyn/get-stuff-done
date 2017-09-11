<template>
    <div class="column is-5 is-centered">
        <div class="content">
            <h2 >
                Create Team
            </h2>
            <p class="control">Give your new team a name</p>
        </div>
        <div class="box">
            <form role="form" @submit.prevent>
                <label for="name" class="label">Your team name</label>
                <p class="control">
                    <input id="name" type="text" class="input" name="name" value="" v-model="team.name" required autofocus>
                <p class="help is-danger" v-text="getErrors('name')"></p>
                <p class="control">
                    <button class="button is-primary" @click="onSubmit">Submit</button>
                </p>
            </form>
        </div>
    </div>
</template>

<script>
    import store from '../../store';
    import Team from '../../core/Team';

    export default {
        data() {
            return{
                team: new Team({name:''})
            }
        },
        components: {

        },
        methods: {
            /** trigger toggle modal event */
            triggerEvent: function(eventName, payload){
                Event.$emit(eventName, payload);
            },
            onSubmit() {
                this.$store.dispatch('SIGN_UP_SUBMIT',this.team);
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
