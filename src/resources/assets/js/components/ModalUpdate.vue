<template>
    <div class="modal fade" ref="vuemodalupdate" id="appModalUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form @keyup.enter="submit">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Edit User #{{ user.id }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" :class="{ 'has-error': errors.name }">
                            <label for="name" class="control-label">Name</label>
                            <input type="text" class="form-control input-lg" v-model="user.name">
                            <span v-if="errors.name" class="help-block">
                                <strong>{{ errors.name[0] }}</strong>
                            </span>
                        </div>
                        <div class="form-group" :class="{ 'has-error': errors.email }">
                            <label for="email" class="control-label">Email</label>
                            <input type="text" class="form-control input-lg" v-model="user.email">
                            <span v-if="errors.email" class="help-block">
                                <strong>{{ errors.email[0] }}</strong>
                            </span>
                        </div>
                        <div class="form-group" :class="{ 'has-error': errors.password }">
                            <label for="password" class="control-label">Password</label>
                            <input type="password" class="form-control input-lg" v-model="user.password">
                            <span v-if="errors.password" class="help-block">
                                <strong>{{ errors.password[0] }}</strong>
                            </span>
                        </div>
                        <div class="form-group" :class="{ 'has-error': errors.passwordConfirmation }">
                            <label for="passwordConfirmation" class="control-label">Password Confirmation</label>
                            <input type="password" class="form-control input-lg" v-model="user.passwordConfirmation">
                            <span v-if="errors.passwordConfirmation" class="help-block">
                                <strong>{{ errors.passwordConfirmation[0] }}</strong>
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
                            <button type="button" class="btn btn-primary" @click="submit">Update</button>
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
            $(this.$refs.vuemodalupdate).on('hidden.bs.modal', this.empty);
        },
        props: ['clearData', 'user'],
        data() {
            return {
                errors: '',
                isDisabled: false,
            }
        },
        methods: {
            submit() {
                this.isDisabled = true;
                axios.put('/api/admin/v1/users/' + this.user.id, {
                    id: this.user.id,
                    name: this.user.name,
                    email: this.user.email,
                    password: this.user.password,
                    passwordConfirmation: this.user.passwordConfirmation,
                    verified: this.user.verified
                })
                .then(response => {
                    if (response.data.success) {
                        this.empty();
                        this.isDisabled = false;
                        this.$emit('updated', response.data.success);
                        $('#appModalUpdate').modal('hide');
                    }
                    if (response.data.errors) {
                        this.errors = response.data.errors;
                    }
                });
            },
            empty() {
                this.errors = '';
                this.$emit('empty', true);
            }
        }
    }
</script>