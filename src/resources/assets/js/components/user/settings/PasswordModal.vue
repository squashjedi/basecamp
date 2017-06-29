<template>
    <div class="modal fade" ref="vuemodelusersettingspassword" id="appModalUserSettingsPassword" tabindex="-1" role="dialog" aria-labelledby="appModalUserSettingsPassword">
        <div class="modal-dialog" role="document">
            <div v-if="status === 'send'" class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Forgot Your Password?</h4>
                </div>
                <div class="modal-body">
                    Would you like an email with a 6 digit code to reset your password?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button v-if="!isSending" type="button" class="btn btn-primary" @click="submitSend"><i class="fa fa-paper-plane" aria-hidden="true"></i> Send</button>
                    <button v-else type="button" class="btn btn-primary disabled"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Sending...</button>
                </div>
            </div>
            <div v-if="status === 'code'" class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Enter Your Code</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            Please check your email and enter the 6 digit code.
                        </div>
                    </div>
                    <div class="form-group" :class="{ 'has-error': errors }">
                        <label for="code" class="control-label">Code</label>
                        <input type="text" class="form-control input-lg" v-model="code">
                        <span v-if="errors" class="help-block">
                            <strong>Please enter the correct code</strong>
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="submitCode">Submit</button>
                </div>
            </div>
            <div v-if="status === 'password'" class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Enter Your New Password</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            You are now authorised to update your password!
                        </div>
                    </div>
                    <div class="form-group" :class="{ 'has-error': errors.passwordNew }">
                        <label for="password" class="control-label">New Password</label>
                        <input type="password" class="form-control input-lg" v-model="passwordNew">
                        <span v-if="errors" class="help-block">
                            <strong>{{ errors.passwordNew[0] }}</strong>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="password-confirmation" class="control-label">Confirm Password</label>
                        <input type="password" class="form-control input-lg" v-model="passwordConfirmation">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button v-if="!isUpdating" type="button" class="btn btn-primary" @click="submitPassword">Update</button>
                    <button v-else type="button" class="btn btn-primary disabled"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Checking...</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.');
            $(this.$refs.vuemodelusersettingspassword).on('hidden.bs.modal', this.empty);
        },
        props: ['user'],
        data() {
            return {
                errors: {},
                status: 'send',
                code: '',
                isSending: false,
                passwordNew: '',
                passwordConfirmation: '',
                isUpdating: false
            }
        },
        methods: {
            submitSend() {
                this.isSending = true;
                axios.post('/api/user/settings/v1/password/send-modal', {
                    id: this.user.id,
                    email: this.user.email,
                })
                .then(response => {
                    if (response.data.success == 'code') {
                        this.errors = '';
                        this.status = 'code';
                    }
                    if (response.data.errors) {
                        this.success = '';
                        this.errors = response.data.errors;
                    }
                    this.isSending = false;
                });
            },
            submitCode() {
                axios.post('/api/user/settings/v1/password/code-modal', {
                    id: this.user.id,
                    email: this.user.email,
                    code: this.code
                })
                .then(response => {
                    if (response.data.success == 'password') {
                        this.errors = '';
                        this.status = 'password';
                    }
                    if (response.data.errors) {
                        this.success = '';
                        this.errors = response.data.errors;
                    }
                });
            },
            submitPassword() {
                this.isUpdating = true;
                axios.post('/api/user/settings/v1/password/update-modal', {
                    id: this.user.id,
                    email: this.user.email,
                    passwordNew: this.passwordNew,
                    passwordConfirmation: this.passwordConfirmation,
                })
                .then(response => {
                    if (response.data.success == 'updated') {
                        this.empty();
                        $('#appModalUserSettingsPassword').modal('hide');
                        this.$emit('alert-new-password', 'You have successfully updated your password!');
                    }
                    if (response.data.errors) {
                        this.success = '';
                        this.errors = response.data.errors;
                        this.isUpdating = false;
                    }
                });
            },
            empty() {
                this.errors = {};
                this.status = 'send';
                this.code = '';
                this.id = '';
                this.email = '';
                this.passwordNew = '';
                this.passwordConfirmation = '';
                this.isUpdating = false;
            }
        }
    }
</script>