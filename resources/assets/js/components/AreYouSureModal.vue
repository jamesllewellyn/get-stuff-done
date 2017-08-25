<template>
    <transition name="modal" mode="out-in">
        <div class="modal" :class="isVisible ? 'is-active' : '' " v-if="isVisible">
            <div class="modal-background"  @click="hideModal()"></div>
            <div class="modal-card modal-container">
                <header class="modal-card-head">
                    <p class="modal-card-title" >Are you sure about that?</p>
                    <button class="delete" @click="hideModal()"></button>
                </header>
                <section class="modal-card-body">
                    <p>Do you really want to <span v-text="action"></span> ?</p>
                </section>
                <footer class="modal-card-foot">
                    <a class="button is-success" :class="{'is-loading': isLoading}" @click="yes">Yes</a>
                    <a class="button" @click="hideModal">Cancel</a>
                </footer>
            </div>
        </div>
    </transition>
</template>

<script>
    import store from '../store';
    export default {
        data() {
            return{
                action: '',
                yesAction:'',
                isVisible: false,
                isLoading: false
            }
        },
        computed:{

        },
        methods: {
            hideModal(){
              this.isVisible = false;
            },
            yes(){
                Event.$emit(this.yesAction);
            }
        },
        created: function() {
            let self = this;
            Event.$on('showAreYouSure', function(action, yesAction) {
                self.action = action;
                self.yesAction = yesAction;
                self.isVisible = true;
            });
            Event.$on('hideAreYouSure', function() {
                self.action = '';
                self.yesAction = '';
                self.isVisible = false;
                self.isLoading = false;
            });
        },
    }
</script>
