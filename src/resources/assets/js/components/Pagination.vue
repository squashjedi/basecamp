<template>
    <div class="text-right paginator">
        <span class="total">{{ recordset.total }} {{ record }}</span>
        <span v-if="recordset.total > recordset.per_page">
            <div class="btn-group">
                <button type="button" class="btn btn-success" @click="link(1)" :disabled="recordset.current_page === 1">
                    <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                </button>
                <button type="button" class="btn btn-success" @click="link(recordset.current_page - 1)" :disabled="recordset.current_page === 1">
                    <i class="fa fa-angle-left" aria-hidden="true"></i>
                </button>
            </div>
            <span>
                <input type="text" class="form-control" v-model="recordset.current_page" @keyup="link(recordset.current_page)"> of {{ recordset.last_page }}
            </span>
            <div class="btn-group">
                <button type="button" class="btn btn-success" @click="link(recordset.current_page + 1)" :disabled="recordset.current_page === recordset.last_page">
                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                </button>
                <button type="button" class="btn btn-success" @click="link(recordset.last_page)" :disabled="recordset.current_page === recordset.last_page">
                    <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                </button>
            </div>
        </span>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.');
        },
        props: ['recordset'],
        data() {
            return {
            }
        },
        methods: {
            link: _.debounce(function(page) {
                if (page < 1) {
                    return;
                }
                if (page > this.recordset.last_page) {
                    page = this.recordset.last_page;
                }
                this.$emit('recordset-changed', page);
            }, 500)
        },
        computed: {
            record() {
                return this.recordset.total === 1 ? 'record' : 'records';
            }
        }
    }
</script>

<style scoped>
    input.form-control {
        text-align: center;
        width: 60px;
        display: inline-block;
    }
</style>