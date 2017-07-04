<template>
	<div>
        <div class="col-md-offset-2 col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                	<ol class="breadcrumb">
						<li><a href="/admin/users">Users</a></li>
						<li class="active">Create</li>
					</ol>
                    <div class="panel-title">
                        Add New User
                    </div>
                </div>
                <div class="panel-body">
                	<squashjedi-basecamp-notify v-if="alert" :message="alert"></squashjedi-basecamp-notify>
                	<form @keyup.enter="publish">
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
	                    <div class="text-right">
	                    	<button type="button" class="btn btn-primary btn-lg" @click="publish">Publish</button>
	                    </div>
	                </form>
                </div>
            </div>
        </div>
	</div>
</template>

<script>
	export default {
        mounted() {
            console.log('Component mounted.');
        },
        data() {
        	return {
                user: {
                    name: '',
                    email: '',
                    password: '',
                    passwordConfirmation: '',
                    verified: 1,
                    errors: '', 
                },
                alert: ''
        	}
        },
        methods: {
            publish() {
                axios.post('/api/admin/v1/users', {
                    name: this.user.name,
                    email: this.user.email,
                    password: this.user.password,
                    passwordConfirmation: this.user.passwordConfirmation,
                    verified: this.user.verified
                })
                .then(response => {
                    if (response.data.success) {
                    	this.alert = response.data.success;
                    	setTimeout(() => this.alert = false, 4000);
                        this.empty();
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

<style>
	
</style>