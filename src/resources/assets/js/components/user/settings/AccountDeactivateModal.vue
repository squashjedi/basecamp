<template>
    <div class="modal fade" ref="vuemodelusersettingsdeactivate" id="appModalUserSettingsDeactivate" tabindex="-1" role="dialog" aria-labelledby="appModalUserSettingsDeactivate">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Deactivate Account</h4>
                </div>
                <div class="modal-body">
                    <p>Please enter your password to deactivate your account.</p>
                    <div class="form-group" :class="{ 'has-error': errors.password }">
                        <label for="password">Password</label>
                        <input type="password" class="form-control input-lg" id="password" v-model="password">
                        <span v-if="errors.password" class="help-block">
                            <strong>{{ errors.password }}</strong>
                        </span>
                        <a :href="linkforgot">Forgot your password?</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="deactivate">Deactivate Account</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.');
            $(this.$refs.vuemodelusersettingsdeactivate).on('hidden.bs.modal', this.empty);
        },
        props: ['linkforgot', 'user'],
        data() {
            return {
                password: '',
                errors: {}
            }
        },
        methods: {
            deactivate() {
                axios.post('/api/user/settings/v1/account/deactivate', {
                    id: this.user.id,
                    password: this.password
                })
                .then(response => {
                    if (response.data.success) {
                        this.success = response.data.success;
                        window.location.href = '/';
                    }
                    if (response.data.errors) {
                        this.success = '';
                        this.errors = response.data.errors;
                    }
                });
            },
            empty() {
                this.password = '';
                this.errors = {};
            }
        }
    }
</script>