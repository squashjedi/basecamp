<template>
    <div>
        <div class="notifications top-right">
            <div class="alert alert-success alert-dismissible fade in out" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Success!</strong> {{ message }}
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
.notifications {
    position: fixed;
    z-index: 9999;
    margin-left: 25px;
}

/* Positioning */ 
.notifications.top-right {
    right: 25px;
    top: 25px;
}

/* Notification Element */
.notifications > div {
    position: relative;
    margin: 5px 0px;
}
</style>