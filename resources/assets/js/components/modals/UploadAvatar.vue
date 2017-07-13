<template>
    <div>
        <section class="modal-card-body">
            <div class="columns">
                <div class="column">
                    <vue-cropper
                            ref="cropper"
                            :img="avatar.img"
                            :outputSize="avatar.size"
                            :outputType="avatar.outputType"
                            :canScale="avatar.canScale"
                            :autoCrop="avatar.autoCrop"
                            :autoCropWidth="avatar.autoCropWidth"
                            :autoCropHeight="avatar.autoCropHeight"
                            :fixed="avatar.fixed"
                            :info="true"
                    ></vue-cropper>
                </div>
            </div>
            <input type="file" id="uploads" accept="image/png, image/jpeg, image/gif, image/jpg" @change="uploadImg">
        </section>
        <footer class="modal-card-foot">
            <a class="button is-success" :class="{'is-loading': isLoading}"  @click="addAvatar()">Save</a>
            <a class="button"  @click="hideModal()">Cancel</a>
        </footer>
    </div>
</template>

<script>
    import store from '../../store';
    import VueCropper from 'vue-cropper';
    export default {
        data() {
            return{
                avatar: {
                    img: '',
                    info: true,
                    size: 1,
                    outputType: 'png',
                    canScale: true,
                    autoCrop: true,
                    autoCropWidth: 150,
                    autoCropHeight: 150,
                    fixed: true,
                }
            }
        },
        components: {
            VueCropper
        },
        computed:{
            isLoading: function() {
                return store.getters.getModalByName('uploadAvatar').isLoading;
            }
        },
        methods: {
            addAvatar () {
                this.$refs.cropper.getCropData((data) => {
                    store.dispatch('ADD_USER_AVATAR', data);
                    store.commit('SET_BUTTON_TO_LOADING', {name : 'uploadAvatar'});
                })
            },
            hideModal(){
                /** clear button loading state **/
                store.commit('REMOVE_BUTTON_LOADING_STATE', {name : 'uploadAvatar'});
                /** call toggle modal mutator **/
                store.commit('TOGGLE_MODAL_IS_VISIBLE', {name : 'uploadAvatar'});
            },
            /** method to get form field errors **/
            getErrors(fieldName) {
                return store.getters.getFormErrors(fieldName);
            },
            realTime (data) {
                this.avatar.previews = data;
            },
            uploadImg (e) {
                /** todo: clean this up */
                // this.option.img
                let file = e.target.files[0];
                if (!/\.(gif|jpg|jpeg|png|bmp|GIF|JPG|PNG)$/.test(e.target.value)) {
                    alert('图片类型必须是.gif,jpeg,jpg,png,bmp中的一种');
                    return false
                }
                let reader = new FileReader();
                reader.onload = (e) => {
                    this.avatar.img = e.target.result;
                };
                reader.readAsDataURL(file)
            }
        }
    }
</script>
