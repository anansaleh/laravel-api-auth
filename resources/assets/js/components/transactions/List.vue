<style>
    .el-dialog__wrapper .el-dialog.el-dialog__header{
        background-color: #3490dc !important;
        color: #f8fafc !important;
    }
</style>
<template>
    <div class="transactions-list">
        <el-row :gutter="20">
            <search-form  :customer_id="customer_id"></search-form>
        </el-row>
        <el-row :gutter="20">
            <div class="text-right">
                <el-button @click="viewForm('new')" type="primary" icon="el-icon-circle-plus"  size="small" >Add New Transaction</el-button>
            </div>
        </el-row>
        <el-row :gutter="20">
            <vuetable ref="vuetable"
                      v-loading="spinner"
                      element-loading-text="Loading data from Server..."
                      :api-url="api_url"

                      pagination-path="meta"
                      :fields="transactions_fields"
                      :css="css"
                      :sort-order="sortOrder"
                      :per-page="10"
                      :http-options="httpOptions"
                      :append-params="appendParams"
                      @vuetable:pagination-data="onPaginationData"
                      @vuetable:loading="vuetableLoading"
                      @vuetable:load-success="vuetableLoadSuccess"
            >
                // Scoped slots https://vuejs.org/v2/guide/components.html#Scoped-Slots
                <template slot="amount" slot-scope="props">
                    {{formatCurrencty(props.rowData.amount)}}
                </template>

                <template slot="actions" slot-scope="props">
                    <el-button-group>
                        <el-button type="primary" icon="el-icon-search" circle  size="mini"
                                   @click="viewTransaction(props.rowData.transaction_id)"></el-button>
                        <el-button type="warning" icon="el-icon-edit" circle  size="mini"
                                   @click="viewForm('edit', props.rowData.transaction_id)"></el-button>
                        <el-button type="danger" icon="el-icon-delete"  size="mini" circle
                                   @click="deleteConfirm(props.rowData)"></el-button>
                    </el-button-group>
                </template>

            </vuetable>
            <div class="vuetable-footer">
                <div class="justify-content-end">
                    <vuetable-pagination-info ref="paginationInfo"></vuetable-pagination-info>
                </div>

                <vuetable-pagination-bootstrap ref="pagination"
                                               @vuetable-pagination:change-page="onChangePage"
                ></vuetable-pagination-bootstrap>
            </div>
        </el-row>
        <el-dialog title="Transaction" :visible.sync="transactionEditForm"  :close-on-click-modal="false" append-to-body>
            <transaction-from v-if="transactionEditForm"
                               :transaction_id="target_transaction"
                               :customer_id="customer_id"
                              @successSaveTransaction="successSave"></transaction-from>
        </el-dialog>
<!--        <el-dialog title="Transaction" :visible.sync="transactionEditForm"  :close-on-click-modal="false" append-to-body>-->
<!--            <transaction-from v-if="transactionEditForm"-->
<!--                              :transaction_id="target_transaction"-->
<!--                              :customer_id="customer_id"-->
<!--                              @successSaveTransaction="successSave"></transaction-from>-->
<!--        </el-dialog>-->
        <el-dialog title="Transaction" :visible.sync="transactionView"  :close-on-click-modal="false" append-to-body>
            <transaction-view v-if="transactionView"
                              :transaction_id="target_transaction"
                              :customer_id="customer_id"></transaction-view>
        </el-dialog>
    </div>
</template>

<script>
    import accounting from 'accounting'
    import moment from 'moment'
    import Vuetable from 'vuetable-2/src/components/Vuetable'
    import VuetablePaginationBootstrap from '../common/VuetablePaginationBootstrap'
    import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'
    import SearchForm from './SearchForm.vue'
    import TransactionFrom from './Form.vue'
    import TransactionView from './View.vue'

    import BootstrapStyle from '../common/bootstrap-css.js'
    export default {
        name: "transactions-list"
        , components: {
            Vuetable
            , VuetablePaginationBootstrap
            , VuetablePaginationInfo
            , SearchForm
            , TransactionFrom
            , TransactionView
        }
        , data() {
            return {
                spinner: true
                //, api_url: ''
                , transactionEditForm: false
                , transactionView: false
                , target_transaction:0
                , httpOptions: { headers: { Authorization: 'Bearer ' + this.$store.state.currentUser.token } }
                , css: BootstrapStyle
                , transactions_fields: [
                    {
                        name: '__sequence',
                        title: '#',
                        titleClass: 'text-center',
                        dataClass: 'text-right'
                    }
                    , {
                        name: 'transaction_id',
                        title: 'ID',
                        titleClass: 'text-center',
                        dataClass: 'text-right',
                        sortField: 'transaction_id'
                    }
                    , {
                        name: 'customer_name',
                        title: 'Customer Name',
                        titleClass: 'text-center',
                        dataClass: 'text-left',
                        sortField: 'customer_name'
                    }
                    // ,{
                    //     name: '__slot:customer_name',
                    //     title: 'Customer Name',
                    //     titleClass: 'text-center',
                    //     dataClass: 'text-left',
                    //     sortField: 'customer_name'
                    // }
                    ,{
                        name: '__slot:amount',
                        title: 'Amount',
                        titleClass: 'text-center',
                        dataClass: 'text-right',
                        sortField: 'amount'
                    }
                    ,{
                        name: 'created_at',
                        title: 'Created at',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        sortField: 'created_at'
                    }
                    ,{
                        name: 'updated_at',
                        title: 'Updated at',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        sortField: 'updated_at'
                    }
                    ,{
                        name: '__slot:actions',
                        title: 'Actions',
                        titleClass: 'text-center',
                        dataClass: 'text-center',

                    }

                ]
                , appendParams: { }
                , sortOrder: [
                    {
                        field: 'created_at',
                        sortField: 'created_at',
                        direction: 'desc'
                    }
                ]
            }
        }
        , props: {
            customer_id:{
                type: Number
                , default: 0
            }
        }
        , created() {
            // this.api_url = (this.customer_id)? '/api/customers/' + this.customer_id + '/transactions' : '/api/transactions';
        }
        , computed: {
            api_url: function(){
                return (this.customer_id)? '/api/customers/' + this.customer_id + '/transactions' : '/api/transactions';
            }
        }
        , mounted() {
            // this.api_url = (this.customer_id > 0)? '/api/customers/' + this.customer_id + '/transactions' : '/api/transactions';
            this.$events.$on('transactions-filter-set', eventData => this.onFilterSet(eventData));
        }
        , methods: {
            vuetableLoadSuccess(response) {
                this.spinner = false;
            }
            , vuetableLoading() {
                this.spinner = true;
            }
            , onPaginationData(paginationData) {
                // console.log('onPaginationData' , paginationData);
                this.$refs.pagination.setPaginationData(paginationData)
                this.$refs.paginationInfo.setPaginationData(paginationData)
            }
            , onChangePage (page) {
                // this.loadData(this.url + '?page='+ page);
                this.$refs.vuetable.changePage(page)
            }
            , formatDate (value, fmt = 'DD.MM.YYYY') {
                return (value)
                    ? moment(value).format(fmt)
                    : '';
            }
            , formatCurrencty (value) {
                return "£" + accounting.formatNumber(value, 2)
            }
            // Footer button action
            , onGroupAction() {
                console.log('Group action. Selected rows: ', this.$refs.vuetable.selectedTo.join(', '));
            }
            , onFilterSet(data) {
                // console.log('datatable');
                this.appendParams = data;
                Vue.nextTick( () => this.$refs.vuetable.refresh())
            }
            , viewForm(act, transaction_id= 0){
                if (act == 'edit'){
                    this.target_transaction= transaction_id;
                }else{
                    this.target_transaction=0;
                }
                this.transactionEditForm= true;
            }
            , viewTransaction(transaction_id){
                this.target_transaction= transaction_id;
                this.transactionView= true
            }
            ,deleteConfirm(transaction) {
                this.$confirm('This will permanently delete the Transaction?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                }).then(() => {

                    this.deleteTransaction(transaction.customer_id, transaction.transaction_id)

                });
                //     .catch(() => {
                //     this.$message({
                //         type: 'info',
                //         message: 'Delete canceled'
                //     });
                // });
            }
            , deleteTransaction(customer_id, transaction_id){
                let self = this;
                let url = '/api/customers/' + customer_id + '/transactions/delete';
                let data={
                        transaction_id: transaction_id
                    };
                self.spinner=true;
                axios({
                    method:'POST'
                    , url: url
                    , data: data
                })
                    .then(function(response) {
                        self.$refs.vuetable.refresh()
                            .then(function () {
                                self.spinner = false;
                                self.$message({
                                    type: 'success',
                                    message: 'Delete completed'
                                });
                            })
                            .catch(e => {
                                self.spinner = false;
                                self.$message({
                                    type:'error'
                                    , message: e.response.data.error.message
                                });
                            });
                    })
                    .catch(e => {
                        self.spinner = false;
                        // console.log(e);
                        // self.$message({
                        //     type:'error'
                        //     , message: e.response.data.error.message
                        // });
                    });
            }
            , successSave(){
                this.transactionEditForm= false;
                this.spinner = true;

                let self = this;
                if(this.target_transaction){
                    this.$refs.vuetable.reload()
                        .then(function () {
                            self.spinner = false;
                            self.$message({
                                type: 'success',
                                message: 'Success update transaction'
                            });
                        })
                        .catch(e => {
                            self.spinner = false;
                            self.$message({
                                type:'error'
                                , message:e.response.data.error.message
                            });
                        });
                }else{
                    this.$refs.vuetable.refresh()
                        .then(function () {
                            self.spinner = false;
                            self.$message({
                                type: 'success',
                                message: 'Success add transaction'
                            });
                        })
                        .catch(e => {
                            self.spinner = false;
                            self.$message({
                                type:'error'
                                , message:e.response.data.error.message
                            });
                        });
                }
            }
        }
    }
</script>

