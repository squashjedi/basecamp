<template>
	<div>
        <squashjedi-basecamp-notify v-if="notify" :message="notify"></squashjedi-basecamp-notify>
        <form @keyup.enter="publish">
            <input type="hidden" v-model="user.id">
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
            <div class="form-group" v-if="!is_edit">
                <a class="btn btn-success btn-lg" @click="is_edit = !is_edit">Edit Password</a>
            </div>
            <template v-else>
                <div class="form-group" :class="{ 'has-error': errors.password }">
                    <label for="password" class="control-label">Password</label>
                    <input type="password" class="form-control input-lg" v-model="user.password">
                    <span v-if="errors.password" class="help-block">
                        <strong>{{ errors.password[0] }}</strong>
                    </span>
                </div>
                <div class="form-group" :class="{ 'has-error': errors.password_confirmation }">
                    <label for="password_confirmation" class="control-label">Password Confirmation</label>
                    <input type="password" class="form-control input-lg" v-model="user.password_confirmation">
                    <span v-if="errors.password_confirmation" class="help-block">
                        <strong>{{ errors.password_confirmation[0] }}</strong>
                    </span>
                </div>
            </template>
            <div class="form-group">
                <label class="control-label">Verified</label><br />
                <label class="radio-inline">
                    <input type="radio" id="1" value="1" v-model="verified"> Yes
                </label>
                <label class="radio-inline">
                    <input type="radio" id="0" value="0" v-model="verified"> No
                </label>
            </div>
            <div class="form-group" :class="{ 'has-error': errors.deleted_at }">
                <label class="control-label">Deactivated</label>
                <div class="input-group date">
                    <input type="text" class="form-control input-lg deleted-at" v-model="user.deleted_at">
                    <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                </div>
                <span v-if="errors.deleted_at" class="help-block">
                    <strong>{{ errors.deleted_at[0] }}</strong>
                </span>
            </div>
            <div class="text-right">
                <button v-if="!is_publishing" type="button" class="btn btn-primary btn-lg" @click="publish">Publish</button>
                <button v-else type="button" class="btn btn-primary disabled"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Publishing Account</button>
            </div>
        </form>
	</div>
</template>

<script>
	export default {
        mounted() {
            console.log('Component mounted.');
            $('.input-group.date').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true
            }).on(
                'changeDate', () => { this.user.deleted_at = $('.deleted-at').val() }
            );
            this.verified = this.user.verified;
            this.is_edit = this.is_password;
        },
        props: ['user', 'errors', 'notify', 'is_password'],
        data() {
            return {
                verified: 1,
                is_edit: false,
                is_publishing: false
            }
        },
        methods: {
            publish() {
                this.$emit('publish', this.verified);
            }
        }
    }
</script>