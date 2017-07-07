<template>
    <div>
        <squashjedi-basecamp-user-settings-account-deactivate-modal :linkforgot="linkforgot" :user="user"></squashjedi-basecamp-user-settings-account-deactivate-modal>
        <div class="text-center">
            <button class="btn btn-primary" data-toggle="modal" data-target="#appModalUserSettingsDeactivate">Deactivate My Account</button>
            <a :href="linkaccount" class="btn btn-default left-padding">Cancel</a>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.');
        },
        props: ['linkaccount', 'linkforgot', 'user']
    }
</script>

<style>
    .left-padding {
        margin-left: 5px;
    }
</style>
