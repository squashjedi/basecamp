<template>
	<div>
        <div class="col-md-offset-2 col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                	<ol class="breadcrumb">
						<li><a href="/admin/users">Users</a></li>
						<li class="active">Edit #{{ userData.id }}</li>
					</ol>
                    <div class="panel-title">
                        Edit User
                    </div>
                </div>
                <div class="panel-body">
                    <basecamp-admin-users-fields
                        :user="userData"
                        :errors="errors"
                        :notify="notify"
                        @publish="publish"></basecamp-admin-users-fields>
                </div>
            </div>
        </div>
	</div>
</template>

<script>
	export default {
        mounted() {
            console.log('Component mounted.');
            this.userData = this.user;
        },
        props: ['user'],
        data() {
        	return {
                errors: '',
                notify: '',
                userData: ''
        	}
        },
        methods: {
            publish(event) {
                clearTimeout(this.delay_notify);
                axios.put('/api/v1/admin/users/' + this.userData.id, {
                    id: this.userData.id,
                    name: this.userData.name,
                    email: this.userData.email,
                    password: this.userData.password,
                    password_confirmation: this.userData.password_confirmation,
                    verified: event.verified,
                    deactivate: event.deactivate
                })
                .then(response => {
                    if (response.data.success) {
                    	this.notify = response.data.success;
                        this.userData = response.data.user;
                        this.delay_notify = setTimeout(() => this.notify = false, 4000);
                        this.errors = '';
                        this.userData.password = '';
                        this.userData.password_confirmation = '';
                        this.deactivate = 0;
                    }
                    if (response.data.errors) {
                        this.errors = response.data.errors;
                    }
                });
            }
        }
    }
</script>

<style>
	
</style>