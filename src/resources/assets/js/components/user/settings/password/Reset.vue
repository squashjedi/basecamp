<template>
    <div>
        <div v-if="success">
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ success }}
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
        <button v-else type="button" class="btn btn-primary btn-lg disabled"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Checking</button>
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
                passwordNew: '',
                passwordConfirmation: ''
            }
        },
        methods: {
            update() {
                this.isSubmit = true;
                axios.post('/api/user/settings/v1/password/update-forgot', {
                    id: this.user.id,
                    email: this.user.email,
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
            empty() {
                this.errors = {};
                this.success = '';
                this.isSubmit = false;
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
