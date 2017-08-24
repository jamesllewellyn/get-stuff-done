<template>
    <div class="dropdown is-hoverable">
        <div class="dropdown-trigger">
            <button class="button" aria-haspopup="true" aria-controls="dropdown-menu" :class="!boarder ? 'has-no-boarder' : ''">
                <slot name="button-text"></slot>
                <span class="icon is-small">
                     <i class="fa fa-angle-down" aria-hidden="true"></i>
                </span>
            </button>
        </div>
        <div class="dropdown-menu" id="dropdown-menu" role="menu">
            <div class="dropdown-content">
                <slot name="dropdowns"></slot>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            boarder:{
                type: Boolean,
                default: true
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
