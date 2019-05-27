<template>
    <div class="transactions-sum">

        <div class="text-center">
            <h3>Transactions Sum Daily</h3>
        </div>
        <el-row :gutter="20">
            <vuetable ref="vuetable"
                      v-loading="spinner"
                      element-loading-text="Loading data from Server..."
                      :api-url="api_url"

                      pagination-path=""
                      :fields="fields"
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
                <template slot="sum_amount" slot-scope="props">
                    {{formatCurrencty(props.rowData.sum_amount)}}
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


    </div>
</template>

<script>
    import accounting from 'accounting'
    import moment from 'moment'
    import Vuetable from 'vuetable-2/src/components/Vuetable'
    import VuetablePaginationBootstrap from '../common/VuetablePaginationBootstrap'
    import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'


    import BootstrapStyle from '../common/bootstrap-css.js'
    export default {
        name: "transactions-sum"
        , components: {
            Vuetable
            , VuetablePaginationBootstrap
            , VuetablePaginationInfo

        }
        , data() {
            return {
                spinner: true
                , httpOptions: { headers: { Authorization: 'Bearer ' + this.$store.state.currentUser.token } }
                , css: BootstrapStyle
                , fields: [

                    , {
                        name: 'trans_daily_id',
                        title: 'ID',
                        titleClass: 'text-center',
                        dataClass: 'text-right',
                        sortField: 'trans_daily_id'
                    }
                    ,{
                        name: 'date_from',
                        title: 'From',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        sortField: 'date_from'
                    }
                    ,{
                        name: 'date_to',
                        title: 'To',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        sortField: 'date_to'
                    }

                    ,{
                        name: '__slot:sum_amount',
                        title: 'Amount',
                        titleClass: 'text-center',
                        dataClass: 'text-right',
                        sortField: 'sum_amount'
                    }
                ]
                , appendParams: { }
                , sortOrder: [
                    {
                        field: 'date_from',
                        sortField: 'date_from',
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
                return  '/api/transactions/daily-sum';
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
        }
    }
</script>

<style scoped>

</style>