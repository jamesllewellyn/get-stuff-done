<template>
    <transition name="modal">
        <div class="modal" :class="isVisible ? 'is-active' : '' ">
            <div class="modal-background"  @click="hideModal()"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title" v-text="title"></p>
                    <button class="delete" @click="hideModal()"></button>
                </header>
                <section class="modal-card-body">
                    <slot name="body"></slot>
                </section>
                <footer class="modal-card-foot">
                    <a class="button is-success">Save changes</a>
                    <a class="button">Cancel</a>
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
        props: ['modalName','title'],
        created: function() {
            let self = this;
            Event.$on(this.modalName, function() {
                console.log('listening...');
                self.isVisible = (self.isVisible == true ? false : true);
            });
        },
        methods: {
            hideModal: function(){
                this.isVisible = false;
            }
        }
    }
</script>
