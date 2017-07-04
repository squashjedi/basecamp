<template>
    <div class="modal fade" ref="vuemodalcreate" id="appModalCreate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form @keyup.enter="submit">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Create New User</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" :class="{ 'has-error': user.errors.name }">
                            <label for="name" class="control-label">Name</label>
                            <input type="text" class="form-control input-lg" v-model="user.name">
                            <span v-if="user.errors.name" class="help-block">
                                <strong>{{ user.errors.name[0] }}</strong>
                            </span>
                        </div>
                        <div class="form-group" :class="{ 'has-error': user.errors.email }">
                            <label for="email" class="control-label">Email</label>
                            <input type="text" class="form-control input-lg" v-model="user.email">
                            <span v-if="user.errors.email" class="help-block">
                                <strong>{{ user.errors.email[0] }}</strong>
                            </span>
                        </div>
                        <div class="form-group" :class="{ 'has-error': user.errors.password }">
                            <label for="password" class="control-label">Password</label>
                            <input type="password" class="form-control input-lg" v-model="user.password">
                            <span v-if="user.errors.password" class="help-block">
                                <strong>{{ user.errors.password[0] }}</strong>
                            </span>
                        </div>
                        <div class="form-group" :class="{ 'has-error': user.errors.passwordConfirmation }">
                            <label for="passwordConfirmation" class="control-label">Password Confirmation</label>
                            <input type="password" class="form-control input-lg" v-model="user.passwordConfirmation">
                            <span v-if="user.errors.passwordConfirmation" class="help-block">
                                <strong>{{ user.errors.passwordConfirmation[0] }}</strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Verified</label><br />
                            <label class="radio-inline">
                                <input type="radio" id="1" value="1" v-model="user.verified"> Yes
                            </label>
                            <label class="radio-inline">
                                <input type="radio" id="0" value="0" v-model="user.verified"> No
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <template>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" @click="submit">Create</button>
                        </template>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.');
            $(this.$refs.vuemodalcreate).on('hidden.bs.modal', this.empty);
        },
        props: ['clearData'],
        data() {
            return {
                user: {
                    name: '',
                    email: '',
                    password: '',
                    passwordConfirmation: '',
                    verified: 1,
                    deleted_at: '',
                    errors: '',
                },
                isDisabled: false,
            }
        },
        methods: {
            submit() {
                this.isDisabled = true;
                axios.post('/api/admin/v1/users', {
                    name: this.user.name,
                    email: this.user.email,
                    password: this.user.password,
                    passwordConfirmation: this.user.passwordConfirmation,
                    verified: this.user.verified,
                    deleted_at: this.user.deleted_at
                })
                .then(response => {
                    if (response.data.success) {
                        this.empty();
                        this.isDisabled = false;
                        this.$emit('created', response.data.success);
                        $('#appModalCreate').modal('hide');
                    }
                    if (response.data.errors) {
                        this.user.errors = response.data.errors;
                    }
                });
            },
            empty() {
                this.user.name = '';
                this.user.email = '';
                this.user.password = '';
                this.user.passwordConfirmation = '';
                this.user.verified = 1;
                this.user.deleted_at = '';
                this.user.errors = '';
            }
        }
    }
</script>