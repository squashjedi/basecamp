<template>
    <div>
        <div v-if="success">
            <div class="alert alert-success alert-dismissible" style="margin-bottom:0px;" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click="success = false"><span aria-hidden="true">&times;</span></button>
                {{ success }}
            </div>
        </div>
        <div v-else>
            <div class="form-group">
                <p>Would you like an email with a password reset link?</p>
            </div>
            <button v-if="!is_sending" type="button" class="btn btn-primary btn-lg" @click="send">Send</button>
            <button v-else type="button" class="btn btn-primary btn-lg disabled"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Sending</button>
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
                is_sending: false,
                success: false
            }
        },
        methods: {
            send() {
                this.is_sending = true;
                this.success = false;
                axios.post('/api/v1/user/settings/password/send', {
                    id: this.user.id,
                    email: this.user.email,
                })
                .then(response => {
                    if (response.data.success) {
                        this.success = response.data.success;
                        this.errors = '';
                    }
                    if (response.data.errors) {
                        this.success = '';
                        this.errors = response.data.errors;
                    }
                    this.is_sending = false;
                });
            }
        }
    }
</script>
