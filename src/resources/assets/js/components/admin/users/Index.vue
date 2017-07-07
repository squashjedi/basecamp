<template>
    <div>
        <squashjedi-basecamp-modal-delete :model="model" :ids="this.checkbox_ids" :record="user" @empty="empty" @ids-deleted="getRecordset"></squashjedi-basecamp-modal-delete>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        Users <a href="/admin/users/create" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> New</a>
                    </div>
                </div>
                <div class="panel-body">
                    <squashjedi-basecamp-notify v-if="notify" :message="notify"></squashjedi-basecamp-notify>
                    <squashjedi-basecamp-pagination :recordset="recordset" @recordset-changed="getResults"></squashjedi-basecamp-pagination>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#appModal" :disabled="is_delete_disabled">
                                        <i aria-hidden="true" class="fa fa-trash"></i>
                                    </button>
                                </th>
                                <th><input type="text" class="form-control" v-model="filter_obj.id" @keyup="filter"></th>
                                <th><input type="text" class="form-control" v-model="filter_obj.name" @keyup="filter"></th>
                                <th><input type="text" class="form-control" v-model="filter_obj.email" @keyup="filter"></th>
                                <th>
                                    <select class="form-control" v-model="filter_obj.verified" @change="filter">
                                        <option value="%">All</option>
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </th>
                                <th>
                                    <select class="form-control" v-model="filter_obj.deleted_at" @change="filter">
                                        <option value="%">All</option>
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center"><input type="checkbox" v-model="is_parent_checkbox" value="true" @change="toggleCheckboxes"></th>
                                <th @click="sort('id')">
                                    <i
                                        :class="{
                                            'fa fa-sort-asc': column.id === 'asc',
                                            'fa fa-sort-desc': column.id === 'desc',
                                            'fa fa-sort': column.id === ''}"
                                        aria-hidden="true"></i> #</th>
                                <th @click="sort('name')">
                                    <i
                                        :class="{
                                            'fa fa-sort-asc': column.name === 'asc',
                                            'fa fa-sort-desc': column.name === 'desc',
                                            'fa fa-sort': column.name === ''}"
                                        aria-hidden="true"></i> Name</th>
                                <th @click="sort('email')">
                                    <i
                                        :class="{
                                            'fa fa-sort-asc': column.email === 'asc',
                                            'fa fa-sort-desc': column.email === 'desc',
                                            'fa fa-sort': column.email === ''}"
                                        aria-hidden="true"></i> Email</th>
                                <th @click="sort('verified')">
                                    <i
                                        :class="{
                                            'fa fa-sort-asc': column.verified === 'asc',
                                            'fa fa-sort-desc': column.verified === 'desc',
                                            'fa fa-sort': column.verified === ''}"
                                        aria-hidden="true"></i> Verified</th>
                                <th @click="sort('deleted_at')">
                                    <i
                                        :class="{
                                            'fa fa-sort-asc': column.deleted_at === 'asc',
                                            'fa fa-sort-desc': column.deleted_at === 'desc',
                                            'fa fa-sort': column.deleted_at === ''}"
                                        aria-hidden="true"></i> Deactivated</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user, index in recordset.data" @mouseenter="record_menu = user.id" @mouseleave="record_menu = 0">
                                <td class="text-center delete-checkbox"><input type="checkbox" v-if="user.id !== 1" :value="user.id" v-model="checkbox_ids" @change="toggleCheckbox"></td>
                                <td>
                                    <div>{{ user.id }}</div>
                                    <div class="small">
                                        <span v-if="record_menu === user.id">
                                            <a :href="'/admin/users/' + user.id">Edit</a>
                                            <template v-if="user.id !== 1">
                                                 | <a data-toggle="modal" data-target="#appModal" @click="getRecord(user)">Delete</a>
                                            </template>
                                            
                                        </span>
                                        <span v-else>&nbsp;</span>
                                    </div>
                                </td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.verified ? 'Yes' : 'No' }}</td>
                                <td>{{ user.deleted_at ? user.deleted_at : 'No' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="panel-body">
                    <squashjedi-basecamp-pagination :recordset="recordset" @recordset-changed="getResults"></squashjedi-basecamp-pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.getRequest(1);
        },
        data() {
            return {
                model: 'Users',
                recordset: {},
                column: {
                    id: 'desc',
                    name: '',
                    email: '',
                    verified: '',
                    deleted_at: ''
                },
                filter_obj: {
                    id: '',
                    name: '',
                    email: '',
                    verified: '%',
                    deleted_at: '%',
                    items: {},
                    filter: ''
                },
                user: {
                    id: '',
                    name: '',
                    email: '',
                    verified: '',
                    deleted_at: ''
                },
                checkbox_ids: [],
                record_menu: '',
                sort_column: '-id',
                delay_notify: '',
                id: 0,
                is_parent_checkbox: false,
                is_delete_disabled: true,
                notify: false,
            }
        },
        methods: {
            empty() {
                this.is_parent_checkbox = false;
                this.user.id = '';
                this.checkbox_ids = [];
            },
            getRecord(user) {
                clearTimeout(this.delay_notify);
                this.notify = false;
                this.checkbox_ids = [];
                this.is_parent_checkbox = false;
                this.user.id = user.id;
            },
            toggleCheckboxes() {
                clearTimeout(this.delay_notify);
                this.notify = false;
                this.updateCheckboxes();
                this.isDeleteButton();

            },
            toggleCheckbox() {
                clearTimeout(this.delay_notify);
                this.notify = false;
                this.isDeleteButton();
            },
            sort: _.debounce(
                function(title) {
                    clearTimeout(this.delay_notify);
                    this.notify = false;
                    this.is_delete_disabled = true;
                    this.is_parent_checkbox = false;
                    this.checkbox_ids = [];
                    this.recordset.current_page = 1;
                    this.sort_column = this.flipSortDirection(title);
                    this.getRequest(this.recordset.current_page);
                }, 500
            ),
            filter: _.debounce(
                function() {
                    clearTimeout(this.delay_notify);
                    this.notify = false;
                    this.is_delete_disabled = true;
                    this.is_parent_checkbox = false;
                    this.checkbox_ids = [];
                    this.recordset.current_page = 1;
                    this.sort_column = this.columnSort();
                    this.filterItems();
                    this.getRequest(this.recordset.current_page);
                }, 500
            ),
            getRecordset(eventMessage) {
                clearTimeout(this.delay_notify);
                this.checkCurrentPage();
                this.sort_column = this.columnSort();
                this.getRequest(this.recordset.current_page);
                this.resetCheckboxIds();
                this.id = 0;
                this.notify = eventMessage;
            },
            getResults(page) {
                clearTimeout(this.delay_notify);
                page = this.checkPage(page);
                this.sort_column = this.columnSort();
                this.getRequest(page);
                this.notify = false;
                this.is_delete_disabled = true;
                this.is_parent_checkbox = false;
                this.checkbox_ids = [];
            },
            columnSort() {
                for (var title in this.column) {
                    if (this.column[title] != '') {
                        if (this.column[title] === 'desc') {
                            return '-' + title;
                        } else {
                            return title;
                        }
                    }
                }
            },
            getRequest(page) {
                axios.get('/api/v1/admin/users?page=' + page + '&sort=' + this.sort_column + '&filter=' + this.filter_obj.filter)
                    .then(response => {
                        this.recordset = response.data;
                        this.delay_notify = setTimeout(() => {
                            setTimeout(() => this.notify = false, 300);
                        }, 4000);
                    });
            },
            resetCheckboxIds() {
                if (this.id === 0) {
                    this.is_delete_disabled = true;
                    this.is_parent_checkbox = false;
                    this.checkbox_ids = [];
                } else {
                    var index = this.checkbox_ids.indexOf(this.id);
                    this.checkbox_ids.splice(index, 1);
                }
            },
            updateCheckboxes() {
                if (this.is_parent_checkbox) {
                    this.recordset.data.forEach((user) => {
                        if (user.id !== 1) {
                            return this.checkbox_ids.push(user.id);
                        }
                    });
                } else {
                    this.checkbox_ids = [];
                }
            },
            isDeleteButton() {
                return this.is_delete_disabled = this.checkbox_ids.length > 0 ? false : true;
            },
            checkPage(page) {
                if (typeof page === 'undefined') {
                    page = 1;
                }
                return page;
            },
            checkCurrentPage() {
                if (this.recordset.current_page === this.recordset.last_page) {
                    if (this.id !== 0) {
                        if (this.recordset.total % this.recordset.per_page === 1) {
                            this.recordset.current_page -= 1;
                            this.recordset.last_page -= 1;
                        }
                    } else if (this.checkbox_ids.length === this.recordset.total % this.recordset.per_page || this.checkbox_ids.length === this.recordset.per_page) {
                        this.recordset.current_page -= 1;
                        this.recordset.last_page -= 1;
                    }
                } else if (this.checkbox_ids.length === this.recordset.total % this.recordset.per_page || this.checkbox_ids.length === this.recordset.per_page) {
                    this.recordset.last_page -= 1;
                }
            },
            flipSortDirection(sort_column) {
                if (this.column[sort_column] === 'asc') {
                    for (var key in this.column) {
                        this.column[key] = '';
                    }
                    this.column[sort_column] = 'desc';
                } else {
                    for (var key in this.column) {
                        this.column[key] = '';
                    }
                    this.column[sort_column] = 'asc';
                }
                return this.column[sort_column] === 'asc' ? sort_column : '-' + sort_column;
            },
            filterItems() {
                for (var key in this.filter_obj) {
                    if (this.filter_obj[key] && key !== 'items' && key !== 'filter') {
                        this.filter_obj.items[key] = this.filter_obj[key];
                    } else {
                        delete this.filter_obj.items[key];
                    }
                }
                this.filter_obj.filter = '';
                for (var key in this.filter_obj.items) {
                    this.filter_obj.filter += key + ':' + this.filter_obj.items[key] + ',';
                }
                this.filter_obj.filter = this.filter_obj.filter.slice(0, -1);
            }
        }
    }
</script>

<style>
    .modal {
        overflow-y: auto;
    }

    .modal-open {
        overflow: auto;
    }

    .modal-open[style] {
        padding-right: 0px !important;
    }
    .panel-title > a {
        color: #fff !important;
    }
    .fa-sort, .fa-sort-asc, .fa-sort-desc {
        color: #b00;
    }
    input[type=checkbox] {
        cursor: pointer;
    }
    a {
        cursor: pointer;
    }
    th {
        color: #333;
        cursor: pointer;
    }
    td {
        min-width: 85px;
    }
    td.delete-checkbox {
        min-width: 0;
    }
    .pagination {
        float: right;
    }
</style>