<template>
    <div>
        <div class="message">
            <div class="alert alert-success fade in out" role="alert">
                {{ message }}
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.');
        },
        props: ['message']
    }
</script>

<style scoped>
    .message {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index:1049;
    }
    .alert {
        margin:0 auto;
        border-bottom: 1px solid transparent;
        border-radius: 0;
    }
</style>