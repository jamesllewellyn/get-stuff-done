<template>
    <ul class="menu-list">

        <li><a>Scout</a></li>
        <li><a>Vision</a></li>
        <li><a>myPeoplesafe</a></li>
        <li><a>Side Project</a></li>
    </ul>
</template>

<script>
    export default {
        props: {
            projects: []
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
