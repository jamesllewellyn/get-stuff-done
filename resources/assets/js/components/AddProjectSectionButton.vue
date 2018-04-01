<template>
    <div class="add-project-section-button"  @click.prevent.stop="triggerEvent('toggleModal','addSection')" >
        <div class="box place-holder has-text-centered ">
            <div class="icon add-icon is-large">
                <i aria-hidden="true" class="fa fa-plus-circle fa-2x is-centered align-vertical"></i>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            /** trigger event */
            triggerEvent: function(eventName, payload){
                Event.$emit(eventName, payload);
            },
        }
    }
</script>
