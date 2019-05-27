<template>
    <div class="customers-list">
        <el-row :gutter="20">
            <div class="text-right">
                <el-button @click="addNew" type="primary" icon="el-icon-circle-plus"  size="small" >Add New</el-button>
            </div>
        </el-row>


        <el-row :gutter="20">
            <vuetable ref="vuetable"
                      v-loading="spinner"
                      element-loading-text="Loading data from Server..."
                      api-url="/api/customers"

                      pagination-path="meta"
                      :fields="customer_fields"
                      :css="css"
                      :sort-order="sortOrder"
                      :per-page="10"
                      :http-options="httpOptions"
                      @vuetable:pagination-data="onPaginationData"
                      @vuetable:loading="vuetableLoading"
                      @vuetable:load-success="vuetableLoadSuccess"
                >


                <template slot="transactions" slot-scope="props">
                    <el-tooltip class="item" effect="dark" content="View Customer Transactions" placement="top">
                        <router-link :to="getLink('view',props.rowData.customer_id)">
                            <el-button type="primary" icon="el-icon-search" circle  size="mini"></el-button>
                        </router-link>
                    </el-tooltip>
                </template>

                <template slot="actions" slot-scope="props">
                    <el-tooltip class="item" effect="dark" content="Edit Customer" placement="top">
                        <router-link :to="getLink('edit', props.rowData.customer_id)">
                            <el-button type="warning" icon="el-icon-edit" circle  size="mini"></el-button>
                        </router-link>
                    </el-tooltip>
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
    import moment from 'moment'
    import Vuetable from 'vuetable-2/src/components/Vuetable'
    import VuetablePaginationBootstrap from '../common/VuetablePaginationBootstrap'
    import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'
    import BootstrapStyle from '../common/bootstrap-css.js'

    export default {
        name: "customers-list"
        , components: {
            Vuetable
            , VuetablePaginationBootstrap
            , VuetablePaginationInfo
        }
        , data(){
            return{
                spinner: true
                , httpOptions: { headers: { Authorization: 'Bearer ' + this.$store.state.currentUser.token } }
                , css: BootstrapStyle
                , customer_fields:[
                    {
                        name: 'customer_id',
                        title: 'ID',
                        titleClass: 'text-center',
                        dataClass: 'text-right',
                        sortField: 'customer_id'
                    }
                    , {
                        name: 'name',
                        title: 'Name',
                        titleClass: 'text-center',
                        dataClass: 'text-left',
                        sortField: 'name'
                    }
                    , {
                        name: 'email',
                        title: 'Email',
                        titleClass: 'text-center',
                        dataClass: 'text-left'
                    }
                    , {
                        name: 'phone',
                        title: 'Phone',
                        titleClass: 'text-center',
                        dataClass: 'text-left'
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
                        name: '__slot:transactions',
                        title: 'Transactions',
                        titleClass: 'text-center',
                        dataClass: 'text-center',

                    }
                    ,{
                        name: '__slot:actions',
                        title: '',
                        titleClass: 'text-center',
                        dataClass: 'text-center',

                    }
                ]
                , sortOrder: [
                    {
                        field: 'name',
                        sortField: 'name',
                        direction: 'asc'
                    }
                ]
            }
        }
        , methods:{
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
            , getLink(action,id){
                if(action == 'view'){
                    return '/customers/'+ id;
                }
                if(action == 'edit'){
                    return '/customers/edit/'+ id;
                }
            }
            , addNew(){
                this.$router.push('/customers/new');
            }
        }
    }
</script>

<style scoped>

</style>