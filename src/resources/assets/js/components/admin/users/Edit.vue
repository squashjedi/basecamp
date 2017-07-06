<template>
	<div>
        <div class="col-md-offset-2 col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                	<ol class="breadcrumb">
						<li><a href="/admin/users">Users</a></li>
						<li class="active">Edit #{{ user.id }}</li>
					</ol>
                    <div class="panel-title">
                        Edit User
                    </div>
                </div>
                <div class="panel-body">
                    <squashjedi-basecamp-admin-users-fields
                        :user="user"
                        :errors="errors"
                        :notify="notify"
                        :is_password="is_password"
                        @publish="publish"></squashjedi-basecamp-admin-users-fields>
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
        props: ['user'],
        data() {
        	return {
                errors: '',
                is_password: false,
                notify: ''
        	}
        },
        methods: {
            publish(verified) {
                clearTimeout(this.delay_notify);
                axios.put('/api/admin/v1/users/' + this.user.id, {
                    id: this.user.id,
                    name: this.user.name,
                    email: this.user.email,
                    password: this.user.password,
                    password_confirmation: this.user.password_confirmation,
                    verified: verified,
                    deleted_at: this.user.deleted_at
                })
                .then(response => {
                    if (response.data.success) {
                    	this.notify = response.data.success;
                        this.delay_notify = setTimeout(() => this.notify = false, 4000);
                        this.errors = '';
                        this.user.password = '';
                        this.user.password_confirmation = '';
                        this.is_password = false;
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