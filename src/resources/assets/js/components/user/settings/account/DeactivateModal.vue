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
                        <label for="password" class="control-label">Password</label>
                        <input type="password" class="form-control input-lg" id="password" v-model="password">
                        <span v-if="errors.password" class="help-block">
                            <strong>{{ errors.password }}</strong>
                        </span>
                        <a :href="linkforgot">Forgot your password?</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button v-if="!isDeactivating" type="button" class="btn btn-primary" @click="deactivate">Deactivate Account</button>
                    <button v-else type="button" class="btn btn-primary disabled"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Deactivating Account</button>
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
                errors: {},
                isDeactivating: false
            }
        },
        methods: {
            deactivate() {
                this.isDeactivating = true;
                axios.post('/api/v1/user/settings/account/deactivate', {
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
                    this.isDeactivating = false;
                });
            },
            empty() {
                this.password = '';
                this.errors = {};
            }
        }
    }
</script>