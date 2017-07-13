<template>
    <div class="notification" :class="status">
        <p><slot></slot></p>
    </div>
</template>

<script>
    export default {
        props:{
          status : {
              required : true
          }
        },
    }
</script>
