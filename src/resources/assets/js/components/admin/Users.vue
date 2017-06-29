<template>
    <div>
        <app-modal-delete :content="modalContent" :ids="this.checkboxIds" @empty="empty" :user="user" @ids-deleted="getRecordset"></app-modal-delete>
        <app-modal-create :clearData="clearData" @created="getRecordsetCreated"></app-modal-create>
        <app-modal-update :clearData="clearData" @updated="getRecordsetUpdated" @empty="empty" :user="user"></app-modal-update>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">Users <button type="button" data-toggle="modal" data-target="#appModalCreate" class="btn btn-primary" @click="clearModalData"><i class="fa fa-plus" aria-hidden="true"></i> New</button></div>
                </div>
                <div class="panel-body">
                    <app-alert v-if="isAlert" :message="alertMessage"></app-alert>
                    <app-pagination :recordset="recordset" @recordset-changed="getResults"></app-pagination>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#appModal" :disabled="isDeleteDisabled">
                                        <i aria-hidden="true" class="fa fa-trash"></i>
                                    </button>
                                </th>
                                <th><input type="text" class="form-control" v-model="filterObj.id" @keyup="filter" @focus="isAlert = false"></th>
                                <th><input type="text" class="form-control" v-model="filterObj.name" @keyup="filter" @focus="isAlert = false"></th>
                                <th><input type="text" class="form-control" v-model="filterObj.email" @keyup="filter" @focus="isAlert = false"></th>
                                <th>
                                    <select class="form-control" v-model="filterObj.verified" @change="filter">
                                        <option value="%">All</option>
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center"><input type="checkbox" v-model="isParentCheckbox" value="true" @change="toggleCheckboxes"></th>
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
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user, index in recordset.data" @mouseenter="recordMenu = user.id" @mouseleave="recordMenu = 0">
                                <td class="text-center delete-checkbox"><input type="checkbox" :value="user.id" v-model="checkboxIds" @change="toggleCheckbox"></td>
                                <td>
                                    <div>{{ user.id }}</div>
                                    <div class="small">
                                        <span v-if="recordMenu === user.id">
                                            <a data-toggle="modal" data-target="#appModalUpdate" @click="getRecord(user)">Edit</a> |
                                            <a data-toggle="modal" data-target="#appModal" @click="getRecord(user)">Delete</a>
                                        </span>
                                        <span v-else>&nbsp;</span>
                                    </div>
                                </td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.verified ? 'Yes' : 'No' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="panel-body">
                    <app-pagination :recordset="recordset" @recordset-changed="getResults"></app-pagination>
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
                modalContent: {
                    title: 'Delete Confirmation',
                    body: 'Are you sure you wish to delete the following records permanently?',
                    type: 'delete'
                },
                recordset: {},
                column: {
                    id: 'asc',
                    name: '',
                    email: '',
                    verified: ''
                },
                filterObj: {
                    id: '',
                    name: '',
                    email: '',
                    verified: '%',
                    items: {},
                    filter: ''
                },
                user: {
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    passwordConfirmation: '',
                    verified: ''
                },
                checkboxIds: [],
                clearData: true,
                recordMenu: '',
                sortColumn: '',
                alertMessage: '',
                delayAlert: '',
                id: 0,
                isParentCheckbox: false,
                isDeleteDisabled: true,
                isAlert: false,
            }
        },
        methods: {
            empty() {
                this.isParentCheckbox = false;
                this.user.id = '';
                this.user.name = '';
                this.user.email = '';
                this.user.password = '';
                this.user.passwordConfirmation = '';
                this.checkboxIds = [];
            },
            clearModalData() {
                clearTimeout(this.delayAlert);
                this.isAlert = false;
            },
            getRecord(user) {
                clearTimeout(this.delayAlert);
                this.isAlert = false;
                this.checkboxIds = [];
                this.isParentCheckbox = false;
                this.user.id = user.id;
                this.user.name = user.name;
                this.user.email = user.email;
                this.user.password = user.password;
                this.user.passwordConfirmation = user.password;
                this.user.verified = user.verified;
            },
            toggleCheckboxes() {
                clearTimeout(this.delayAlert);
                this.isAlert = false;
                this.updateCheckboxes();
                this.isDeleteButton();

            },
            toggleCheckbox() {
                clearTimeout(this.delayAlert);
                this.isAlert = false;
                this.isDeleteButton();
            },
            sort: _.debounce(
                function(title) {
                    clearTimeout(this.delayAlert);
                    this.isAlert = false;
                    this.isDeleteDisabled = true;
                    this.isParentCheckbox = false;
                    this.checkboxIds = [];
                    this.recordset.current_page = 1;
                    this.sortColumn = this.flipSortDirection(title);
                    this.getRequest(this.recordset.current_page);
                }, 500
            ),
            filter: _.debounce(
                function() {
                    clearTimeout(this.delayAlert);
                    this.isAlert = false;
                    this.isDeleteDisabled = true;
                    this.isParentCheckbox = false;
                    this.checkboxIds = [];
                    this.recordset.current_page = 1;
                    this.sortColumn = this.columnSort();
                    this.filterItems();
                    this.getRequest(this.recordset.current_page);
                }, 500
            ),
            getRecordsetCreated(eventMessage) {
                clearTimeout(this.delayAlert);
                this.filterObj.filter = '';
                this.sortColumn = '-id'
                this.column.id = "desc";
                this.getRequest(1);
                this.resetCheckboxIds();
                this.id = 0;
                this.isAlert = true;
                this.alertMessage = eventMessage;
            },
            getRecordsetUpdated(eventMessage) {
                clearTimeout(this.delayAlert);
                this.getRequest(this.recordset.current_page);
                this.isAlert = true;
                this.alertMessage = eventMessage;
            },
            getRecordset(eventMessage) {
                clearTimeout(this.delayAlert);
                this.checkCurrentPage();
                this.sortColumn = this.columnSort();
                this.getRequest(this.recordset.current_page);
                this.resetCheckboxIds();
                this.id = 0;
                this.isAlert = true;
                this.alertMessage = eventMessage;
            },
            getResults(page) {
                clearTimeout(this.delayAlert);
                page = this.checkPage(page);
                this.sortColumn = this.columnSort();
                this.getRequest(page);
                this.isAlert = false;
                this.isDeleteDisabled = true;
                this.isParentCheckbox = false;
                this.checkboxIds = [];
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
                axios.get('/api/admin/v1/users?page=' + page + '&sort=' + this.sortColumn + '&filter=' + this.filterObj.filter)
                    .then(response => {
                        this.recordset = response.data;
                        this.delayAlert = setTimeout(() => {
                            $('.alert').alert('close');
                            setTimeout(() => this.isAlert = false, 300);
                        }, 4000);
                    });
            },
            resetCheckboxIds() {
                if (this.id === 0) {
                    this.isDeleteDisabled = true;
                    this.isParentCheckbox = false;
                    this.checkboxIds = [];
                } else {
                    var index = this.checkboxIds.indexOf(this.id);
                    this.checkboxIds.splice(index, 1);
                }
            },
            updateCheckboxes() {
                if (this.isParentCheckbox) {
                    this.recordset.data.forEach(user => this.checkboxIds.push(user.id));
                } else {
                    this.checkboxIds = [];
                }
            },
            isDeleteButton() {
                return this.isDeleteDisabled = this.checkboxIds.length > 0 ? false : true;
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
                    } else if (this.checkboxIds.length === this.recordset.total % this.recordset.per_page || this.checkboxIds.length === this.recordset.per_page) {
                        this.recordset.current_page -= 1;
                        this.recordset.last_page -= 1;
                    }
                } else if (this.checkboxIds.length === this.recordset.total % this.recordset.per_page || this.checkboxIds.length === this.recordset.per_page) {
                    this.recordset.last_page -= 1;
                }
            },
            flipSortDirection(sortColumn) {
                if (this.column[sortColumn] === 'asc') {
                    for (var key in this.column) {
                        this.column[key] = '';
                    }
                    this.column[sortColumn] = 'desc';
                } else {
                    for (var key in this.column) {
                        this.column[key] = '';
                    }
                    this.column[sortColumn] = 'asc';
                }
                return this.column[sortColumn] === 'asc' ? sortColumn : '-' + sortColumn;
            },
            filterItems() {
                for (var key in this.filterObj) {
                    if (this.filterObj[key] && key !== 'items' && key !== 'filter') {
                        this.filterObj.items[key] = this.filterObj[key];
                    } else {
                        delete this.filterObj.items[key];
                    }
                }
                this.filterObj.filter = '';
                for (var key in this.filterObj.items) {
                    this.filterObj.filter += key + ':' + this.filterObj.items[key] + ',';
                }
                this.filterObj.filter = this.filterObj.filter.slice(0, -1);
            }
        }
    }
</script>

<style scoped>
    .fa-sort, .fa-sort-asc, .fa-sort-desc {
        color: #b00;
    }
    input[type=checkbox] {
        cursor: pointer;
    }
    a {
        cursor: pointer;
        text-decoration: none;
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