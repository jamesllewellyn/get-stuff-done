<template>
    <div>
        <section class="modal-card-body">
            <form>
                <div class="field">
                    <label class="label">Section</label>
                    <p class="control">
                        <input class="input" type="text" name="name" placeholder="Section Name" v-model="section.name">
                    </p>
                    <p class="help is-danger" v-text="getErrors('name')"></p>
                </div>
            </form>
        </section>
        <footer class="modal-card-foot">
            <a class="button is-success" :class="{'is-loading': isLoading}"  @click="addSection()">Save changes</a>
        </footer>
    </div>
</template>

<script>
    import Section from '../../core/Section';
    import store from '../../store';
    export default {
        data() {
            return{
                section: new Section({id:'', name:'', tasks: [], created_at: ''}),
                modalName: 'addSection',
            }
        },
        props: {
            projectId : {
                required: true
            }
        },
        computed:{
            isLoading: function() {
                return store.getters.getModalByName(this.modalName).isLoading;
            }
        },
        methods: {
            addSection () {
                /** set modal save button to loading status **/
                store.commit('SET_BUTTON_TO_LOADING', {name : this.modalName});
                /** dispatch add new project action **/
                store.dispatch('ADD_NEW_SECTION', {projectId: this.projectId, section:this.section });
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
