<template>
    <div class="modal fade" ref="vuemodaldelete" id="appModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ content.title }}</h4>
                </div>
                <div class="modal-body">
                    {{ content.body }}
                    <div v-if="user.id">
                        #{{ user.id }}
                    </div>
                    <div v-else>
                        {{ userIds }}
                    </div>
                </div>
                <div class="modal-footer">
                    <template v-if="content.type === 'delete'">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" @click="deleteRecords">
                            <i aria-hidden="true" class="fa fa-trash"></i> Delete
                        </button>
                    </template>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.');
            $(this.$refs.vuemodaldelete).on('hidden.bs.modal', this.empty);
        },
        props: ['content', 'ids', 'user'],
        data() {
            return {
                recordIds: '',
                deleteIds: '',
                message: ''
            }
        },
        computed: {
            userIds() {
                this.recordIds = this.ids;
                return this.recordIds.sort((a, b) => a - b).map(item => '#' + item).join(', ');
            }
        },
        methods: {
            deleteRecords() {
                if (this.user.id !== '') {
                    this.deleteIds = this.user.id;
                } else {
                    this.deleteIds = this.ids;
                }
                axios.delete('/api/admin/v1/users/' + this.deleteIds)
                    .then(response => {
                        this.response = response.data;
                        this.$emit('ids-deleted', this.response.success);
                        this.empty();
                    });
                $('#appModal').modal('hide');
            },
            empty() {
                if (this.user.id !== '') {
                    this.$emit('empty', true);
                }
            }
        }
    }
</script>