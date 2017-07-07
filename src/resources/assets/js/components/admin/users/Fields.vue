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
            <div class="form-group">
                <label class="control-label">Verified</label><br />
                <label class="radio-inline">
                    <input type="radio" id="1" value="1" v-model="user.verified"> Yes
                </label>
                <label class="radio-inline">
                    <input type="radio" id="0" value="0" v-model="user.verified"> No
                </label>
            </div>
            <div class="form-group">
                <label class="control-label">Deactivated At</label><br />
                <input type="text" class="form-control input-lg" disabled v-model="user.deleted_at">

            </div>
            <div class="form-group">
                <label class="radio-inline">
                    <input type="radio" id="2" value="2" v-model="deactivate"> Deactivate
                </label>
                <label class="radio-inline">
                    <input type="radio" id="1" value="1" v-model="deactivate"> Remove
                </label>
                <label class="radio-inline">
                    <input type="radio" id="0" value="0" v-model="deactivate"> Unalter
                </label>
            </div>
            <div class="text-right">
                <button v-if="!is_publishing" type="button" class="btn btn-primary btn-lg" @click="publish">Publish</button>
                <button v-else type="button" class="btn btn-primary disabled"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Publishing</button>
            </div>
        </form>
	</div>
</template>

<script>
	export default {
        mounted() {
            console.log('Component mounted.');
        },
        props: ['user', 'errors', 'notify'],
        data() {
            return {
                is_publishing: false,
                deactivate: 0
            }
        },
        methods: {
            publish() {
                this.$emit('publish', {verified: this.user.verified, deactivate: this.deactivate});
                this.deactivate = 0;
            }
        }
    }
</script>