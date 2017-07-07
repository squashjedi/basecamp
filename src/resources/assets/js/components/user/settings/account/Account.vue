<template>
    <div>
        <form @keyup.enter="submit">
            <div v-if="success">
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ success }}
                </div>
            </div>
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
            <button v-if="!isSubmit" type="button" class="btn btn-primary btn-lg" @click="submit">Update</button>
            <button v-else type="button" class="btn btn-primary btn-lg disabled"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Updating</button>
        </form>

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
                isSubmit: false
            }
        },
        methods: {
            submit() {
                this.errors = '';
                this.success = '';
                this.isSubmit = true,
                axios.put('/api/v1/user/settings/account/' + this.user.id, {
                    id: this.user.id,
                    name: this.user.name,
                    email: this.user.email,
                })
                .then(response => {
                    if (response.data.success) {
                        this.success = response.data.success;
                        this.errors = '';
                        if (response.data.logout) {
                            axios.post('/logout');
                        }
                    }
                    if (response.data.errors) {
                        this.success = '';
                        this.errors = response.data.errors;
                    }
                    this.isSubmit = false;
                });
            }
        }
    }

</script>

<style>

</style>
