<template>
    <div class="dropdown is-hoverable" >
        <div class="dropdown-trigger">
            <button class="button" aria-haspopup="true" aria-controls="dropdown-menu" :class="!boarder ? 'has-no-boarder' : ''" >
                <span class="icon is-small">
                     <i class="fa fa-angle-down" aria-hidden="true"></i>
                </span>
            </button>
        </div>
        <div class="dropdown-menu" id="dropdown-menu" role="menu" ref="dropdown" @blur="hideDropdown">
            <div class="dropdown-content" >
                    <a class="dropdown-item" v-for="dropdown in dropdowns" @click="triggerEvent(dropdown)">
                       {{dropdown.text}}
                    </a>
                </span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return{
                isActive: false
            }
        },
        props: {
            boarder:{
                type: Boolean,
                default: true
            },
            dropdowns:{
                type: Array,
                required: true
            }
        },
        methods: {
            /** trigger event */
            triggerEvent(object){
                if(object.areYouSure){
                    Event.$emit('showAreYouSure', object.action, object.event);
                    return true
                }
                Event.$emit(object.event);
            },
            showDropdown(){
                this.isActive = true;
                this.$refs.dropdown.focus();
            },
            hideDropdown(){
                this.isActive = false;
            }
        }
    }
</script>
