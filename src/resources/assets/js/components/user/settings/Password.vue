<template>
    <div>
        <app-user-settings-password-modal :user="user" @alert-new-password="alertNewPassword"></app-user-settings-password-modal>
        <div v-if="success">
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ success }}
            </div>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.passwordCurrent }">
            <label for="password" class="control-label">Current Password</label>
            <input type="password" class="form-control input-lg" v-model="passwordCurrent">
            <span v-if="errors.passwordCurrent" class="help-block">
                <strong>{{ errors.passwordCurrent[0] }}</strong>
            </span>{{ user.passordCurrent }}
            <div>
                <a data-toggle="modal" data-target="#appModalUserSettingsPassword" @click="empty">
                    Forgot Your Password?
                </a>
            </div>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.passwordNew }">
            <label for="new-password" class="control-label">New Password</label>
            <input type="password" class="form-control input-lg" v-model="passwordNew">
            <span v-if="errors.passwordNew" class="help-block">
                <strong>{{ errors.passwordNew[0] }}</strong>
            </span>
        </div>
        <div class="form-group">
            <label for="confirm-password" class="control-label">Confirm Password</label>
            <input type="password" class="form-control input-lg" v-model="passwordConfirmation">
        </div>
        <button v-if="!isSubmit" type="button" class="btn btn-primary btn-lg" @click="update">Update</button>
        <button v-else type="button" class="btn btn-primary btn-lg disabled"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Checking...</button>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.');
        },
        props: ['user'],
        data() {
            return {
                errors: {},
                success: '',
                isSubmit: false,
                passwordCurrent: '',
                passwordNew: '',
                passwordConfirmation: ''
            }
        },
        methods: {
            update() {
                this.isSubmit = true;
                axios.post('/api/user/settings/v1/password/update', {
                    id: this.user.id,
                    email: this.user.email,
                    passwordCurrent: this.passwordCurrent,
                    passwordNew: this.passwordNew,
                    passwordConfirmation: this.passwordConfirmation,
                })
                .then(response => {
                    if (response.data.success == 'updated') {
                        this.empty();
                        this.success = 'You have successfully updated your password!';
                        this.isSubmit = false;
                        $(this).blur();
                    }
                    if (response.data.errors) {
                        this.success = '';
                        this.errors = response.data.errors;
                        this.isSubmit = false;
                    }
                });
            },
            alertNewPassword($event) {
                this.empty();
                this.success = $event;
            },
            empty() {
                this.errors = {};
                this.success = '';
                this.isSubmit = false;
                this.passwordCurrent = '';
                this.passwordNew = '';
                this.passwordConfirmation = '';
            }
        }
    }

</script>

<style scoped>
    a {
        cursor: pointer;
    }
</style>
