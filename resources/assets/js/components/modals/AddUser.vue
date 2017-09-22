<template>
    <div>
        <section class="modal-card-body">
            <form>
                <div class="field">
                    <label class="label">Members Email</label>
                    <p class="control">
                        <input class="input" type="email" name="name"  v-model="email">
                    </p>
                    <p class="help is-danger" v-text="getErrors('email')"></p>
                </div>
            </form>
        </section>
        <footer class="modal-card-foot">
            <a class="button is-success" :class="{'is-loading': isLoading}"  @click="addUser()">Add new member</a>
            <a class="button"  @click="hideModal()">Cancel</a>
        </footer>
    </div>
</template>

<script>
    import Project from '../../core/Project';
    import store from '../../store';
    export default {
        data() {
            return{
               email: ''
            }
        },
        computed:{
            isLoading: function() {
                return store.getters.getModalByName('addUser').isLoading;
            }
        },
        methods: {
            addUser () {
                /** set modal save button to loading status **/
                store.commit('SET_BUTTON_TO_LOADING', {name : 'addUser'});
                /** dispatch add user action **/
                store.dispatch('ADD_TEAM_MEMBER', this.email);
            },
            hideModal(){
                /** clear button loading state **/
                store.commit('REMOVE_BUTTON_LOADING_STATE', {name : 'addUser'});
                /** call toggle modal mutator **/
                store.commit('TOGGLE_MODAL_IS_VISIBLE', {name : 'addUser'});
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
