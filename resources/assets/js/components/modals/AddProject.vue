<template>
    <div>
        <section class="modal-card-body">
            <form>
                <div class="field">
                    <label class="label">Project Name</label>
                    <p class="control">
                        <input class="input" type="text" name="name" placeholder="Project Name" v-model="project.name">
                    </p>
                    <p class="help is-danger" v-text="getErrors('name')"></p>
                </div>
            </form>
        </section>
        <footer class="modal-card-foot">
            <a class="button is-success" :class="{'is-loading': isLoading}"  @click="addProject()">Save changes</a>
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
                project : new Project({id:'', name:'', sections: [], created_at: ''}),
            }
        },
        computed:{
            isLoading: function() {
                return store.getters.getModalByName('addProject').isLoading;
            }
        },
        methods: {
            addProject () {
                /** set modal save button to loading status **/
                store.commit('SET_BUTTON_TO_LOADING', {name : 'addProject'});
                /** dispatch add new project action **/
                store.dispatch('ADD_NEW_PROJECT', this.project);
            },
            hideModal(){
                /** clear button loading state **/
                store.commit('REMOVE_BUTTON_LOADING_STATE', {name : 'addProject'});
                /** call toggle modal mutator **/
                store.commit('TOGGLE_MODAL_IS_VISIBLE', {name : 'addProject'});
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
