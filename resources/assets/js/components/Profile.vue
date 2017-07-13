<template>
    <div class="task-wrapper">
        <div class="task-background modal-background is-fullheight" @click.prevent.stop="triggerEvent('toggleProfile')" v-if="profileVisible">
        </div>
        <transition name="slide">
            <aside class="profile task hero is-fullheight has-shadow" v-if="profileVisible">
                <div class="header">
                    <div class="level">
                        <h2 class="has-text-centered">  Profile</h2>
                        <img :src="user.avatar_url" class="circle">
                    </div>
                </div>
                <div class="body">
                    <div class="field">
                        <label class="label">Name</label>
                        <p class="control">
                            <input class="input" type="text" placeholder="Text input" v-model="user.name" @change="updateUser">
                        </p>
                    </div>
                    <div class="field">
                        <label class="label">Email</label>
                        <p class="control">
                            <input class="input" type="text" placeholder="Text input" v-model="user.email" @change="updateUser">
                        </p>
                    </div>
                    <div class="field">
                        <label class="label">Avatar</label>
                        <p class="control">
                            <a class="button" @click="triggerEvent('toggleModal', 'uploadAvatar')">Set Avatar</a>
                        </p>
                    </div>
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
        computed:
            mapState([
                'profileVisible', 'user'
            ]),
        components: {
            UploadAvatar, Modal
        },
        methods:{
            updateUser:function(){
                this.$store.dispatch('UPDATE_USER', {user :this.user})
            },
            triggerEvent: function(eventName, payLoad){
                Event.$emit(eventName, payLoad);
            }
        },
    }
</script>
