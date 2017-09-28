<template>
    <div class="task-wrapper">
        <!--<div class="task-background modal-background is-fullheight" @click.prevent.stop="triggerEvent('toggleProfile')" v-if="profileVisible">-->
        <!--</div>-->
        <transition name="modal" mode="out-in">
            <div class="modal-background" @click.prevent.stop="triggerEvent('toggleProfile')" v-if="profileVisible"></div>
        </transition>
        <transition name="slide">
            <aside class="profile task hero is-fullheight has-shadow" v-if="profileVisible">
                <div class="header">
                    <div class="level">
                        <h2 class="has-text-centered">Profile</h2>
                        <img :src="user.avatar_url" class="circle">
                    </div>
                </div>
                <div class="body">
                    <div class="field">
                        <label class="label">First Name</label>
                        <p class="control">
                            <input class="input" type="text" placeholder="Text input" v-model="user.first_name" @change="updateUser">
                        </p>
                        <p class="help is-danger" v-text="getErrors('first_name')"></p>
                    </div>
                    <div class="field">
                        <label class="label">Last Name</label>
                        <p class="control">
                            <input class="input" type="text" placeholder="Text input" v-model="user.last_name" @change="updateUser">
                        </p>
                        <p class="help is-danger" v-text="getErrors('last_name')"></p>
                    </div>
                    <div class="field">
                        <label class="label">Email</label>
                        <p class="control">
                            <input disabled class="input disabled" type="text" placeholder="Text input" v-model="user.email" @change="updateUser">
                        </p>
                    </div>
                    <div class="field">
                        <label class="label">Handle</label>
                        <p class="control">
                            <input class="input" type="text" placeholder="Text input" v-model="user.handle" @change="updateUser">
                        </p>
                        <p class="help is-danger" v-text="getErrors('handle')"></p>
                    </div>
                    <!--<div class="field">-->
                        <!--<label class="label">Avatar</label>-->
                        <!--<p class="control">-->
                            <!--<a class="button" @click="triggerEvent('toggleModal', 'uploadAvatar')">Set Avatar</a>-->
                        <!--</p>-->
                    <!--</div>-->
                </div>
            </aside>
        </transition>
        <modal modal-name="uploadAvatar" title="Add Avatar">
            <template slot="body">
                <upload-avatar></upload-avatar>
            </template>
        </modal>
    </div>
</template>

<script>
    import store from '../store';
    import { mapState, mapGetters } from 'vuex';
    import Modal from '../components/Modal.vue';
    import UploadAvatar from '../components/modals/UploadAvatar.vue';
    export default {
        components: {
            UploadAvatar, Modal
        },
        computed:{
            user () { return  this.$store.state.user },
            profileVisible () {return  this.$store.state.profileVisible }
        },
        methods:{
            updateUser:function(){
                this.$store.dispatch('UPDATE_USER', this.user)
            },
            triggerEvent: function(eventName, payLoad){
                Event.$emit(eventName, payLoad);
            },
            /** method to get form field errors **/
            getErrors(fieldName) {
                return store.getters.getFormErrors(fieldName);
            }
        },
    }
</script>
