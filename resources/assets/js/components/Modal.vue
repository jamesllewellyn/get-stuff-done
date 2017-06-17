<template>
    <transition name="modal" mode="out-in">
        <div class="modal" :class="isVisible ? 'is-active' : '' " v-if="isVisible">
            <div class="modal-background"  @click="hideModal()"></div>
            <div class="modal-card modal-container">
                <header class="modal-card-head">
                    <p class="modal-card-title" v-text="title"></p>
                    <button class="delete" @click="hideModal()"></button>
                </header>
                <section class="modal-card-body">
                    <slot name="body"></slot>
                </section>
                <footer class="modal-card-foot">
                    <a class="button is-success" :class="{ 'is-loading': isLoading}" @click="onSubmit()">Save changes</a>
                    <a class="button"  @click="hideModal()">Cancel</a>
                </footer>
            </div>
        </div>
    </transition>
</template>

<script>

    export default {
        data() {
            return{
                isVisible : false
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
            isLoading:{
                type: Boolean,
                default: false
            },
        },
        created: function() {
            let self = this;
            Event.$on(this.modalName, function() {
                self.isVisible = (self.isVisible == true ? false : true);
            });
        },
        methods: {
            hideModal: function(){
                this.isVisible = false;
            },
            onSubmit: function(){
                this.isLoading = true;
                Event.$emit('modalSubmit', this.modalName);
            }
        }
    }
</script>
