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
                    <basecamp-admin-users-fields
                        :user="user"
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
        },
        data() {
        	return {
                user: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    verified: 1, 
                },
                errors: '',
                is_password: true,
                notify: '',
                deactivate: ''
        	}
        },
        methods: {
            publish(event) {
                clearTimeout(this.delay_notify);
                axios.post('/api/v1/admin/users', {
                    name: this.user.name,
                    email: this.user.email,
                    password: this.user.password,
                    password_confirmation: this.user.password_confirmation,
                    verified: event.verified,
                    deactivate: event.deactivate
                })
                .then(response => {
                    if (response.data.success) {
                    	this.notify = response.data.success;
                        this.delay_notify = setTimeout(() => this.notify = false, 4000);
                        this.empty();
                    }
                    if (response.data.errors) {
                        this.errors = response.data.errors;
                    }
                });
            },
            empty() {
                this.user.name = '';
                this.user.email = '';
                this.user.password = '';
                this.user.password_confirmation = '';
                this.user.verified = 1;
                this.user.deleted_at = '';
                this.errors = '';
            }
        }
    }
</script>

<style>
	
</style>