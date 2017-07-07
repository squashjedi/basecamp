<template>
    <div class="modal fade" ref="vuemodaldelete" id="appModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
                </div>
                <div class="modal-body">
                    Are you sure you wish to delete the following {{ model }} permanently?
                    <div v-if="record.id">
                        #{{ record.id }}
                    </div>
                    <div v-else>
                        {{ recordsetIds }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="deleteRecords">
                        <i aria-hidden="true" class="fa fa-trash"></i> Delete
                    </button>
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
        props: ['model', 'ids', 'record'],
        data() {
            return {
                recordIds: '',
                deleteIds: '',
                message: ''
            }
        },
        computed: {
            recordsetIds() {
                this.recordIds = this.ids;
                return this.recordIds.sort((a, b) => a - b).map(item => '#' + item).join(', ');
            }
        },
        methods: {
            deleteRecords() {
                if (this.record.id !== '') {
                    this.deleteIds = this.record.id;
                } else {
                    this.deleteIds = this.ids;
                }
                axios.delete('/api/v1/admin/' + this.model.toLowerCase() +'/' + this.deleteIds)
                    .then(response => {
                        this.response = response.data;
                        this.$emit('ids-deleted', this.response.success);
                        this.empty();
                    });
                $('#appModal').modal('hide');
            },
            empty() {
                if (this.record.id !== '') {
                    this.$emit('empty', true);
                }
            }
        }
    }
</script>