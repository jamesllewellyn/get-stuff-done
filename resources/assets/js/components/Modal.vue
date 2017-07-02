<template>
    <transition name="modal" mode="out-in">
        <div class="modal" :class="modal.isVisible ? 'is-active' : '' " v-if="modal.isVisible">
            <div class="modal-background"  @click="hideModal()"></div>
            <div class="modal-card modal-container">
                <header class="modal-card-head">
                    <p class="modal-card-title" v-text="title"></p>
                    <button class="delete" @click="hideModal()"></button>
                </header>
                <slot name="body"></slot>
            </div>
        </div>
    </transition>
</template>

<script>
    import store from '../store';
    export default {
        data() {
            return{
            }
        },
        props: {
            modalName : {
                type: String,
                required: true
            },
            title: {
                type: String,
                required: true
            },
        },
        computed:{
            modal: function(){
                return store.getters.getModalByName(this.modalName);
            }
        },
        created: function() {
            console.log('Modal mounted');
            store.commit('ADD_MODAL',  { name:  this.modalName })
        },
        methods: {
            hideModal: function(){
                store.commit('TOGGLE_MODAL_IS_VISIBLE', {name : this.modalName});
            },
        }
    }
</script>
