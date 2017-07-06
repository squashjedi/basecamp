<template>
    <div>
        <app-user-settings-password-modal :user="user" @alert-new-password="alertNewPassword"></app-user-settings-password-modal>
        <div v-if="success">
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ success }}
            </div>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.password_current }">
            <label for="password" class="control-label">Current Password</label>
            <input type="password" class="form-control input-lg" v-model="password_current">
            <span v-if="errors.password_current" class="help-block">
                <strong>{{ errors.password_current[0] }}</strong>
            </span>
            <div>
                <a href="/user/settings/password/forgot">
                    Forgot your password?
                </a>
            </div>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.password_new }">
            <label for="new-password" class="control-label">New Password</label>
            <input type="password" class="form-control input-lg" v-model="password_new">
            <span v-if="errors.password_new" class="help-block">
                <strong>{{ errors.password_new[0] }}</strong>
            </span>
        </div>
        <div class="form-group">
            <label for="confirm-password" class="control-label">Confirm Password</label>
            <input type="password" class="form-control input-lg" v-model="password_confirmation">
        </div>
        <button v-if="!is_submit" type="button" class="btn btn-primary btn-lg" @click="update">Update</button>
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
                is_submit: false,
                password_current: '',
                password_new: '',
                password_confirmation: ''
            }
        },
        methods: {
            update() {
                this.is_submit = true;
                axios.post('/api/user/settings/v1/password/update', {
                    id: this.user.id,
                    email: this.user.email,
                    password_current: this.password_current,
                    password_new: this.password_new,
                    password_confirmation: this.password_confirmation,
                })
                .then(response => {
                    if (response.data.success == 'updated') {
                        this.empty();
                        this.success = 'Your password has been successfully updated!';
                        this.is_submit = false;
                        $(this).blur();
                    }
                    if (response.data.errors) {
                        this.success = '';
                        this.errors = response.data.errors;
                        this.is_submit = false;
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
                this.is_submit = false;
                this.password_current = '';
                this.password_new = '';
                this.password_confirmation = '';
            }
        }
    }

</script>

<style scoped>
    a {
        cursor: pointer;
    }
</style>
