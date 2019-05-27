<style>
    .well {
        min-height: 20px;
        padding: 19px;
        margin-bottom: 20px;
        background-color: #f5f5f5;
        border: 1px solid #e3e3e3;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
        box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
    }
</style>
<template>
    <div class="search-form well">
        <el-form label-position="right" label-width="130px" :model="filters">
            <el-row v-if="customer_id < 1" :gutter="20">
                <el-col :span="12">
                    <el-form-item label="Customer ID">
                        <el-input v-model="filters.filter_customer_id"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item label="Customer Name">
                        <el-input v-model="filters.filter_customer_name"></el-input>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col :span="12">
                    <el-form-item label="Transaction ID">
                        <el-input v-model="filters.filter_transaction_id"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="6">
                    <el-form-item label="Amount">
                        <el-select v-model="filters.filter_amount_operation" placeholder="Select">
                            <el-option value="="></el-option>
                            <el-option value=">="></el-option>
                            <el-option value="<="></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="6">
                    <el-input v-model="filters.filter_amount"></el-input>
                </el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col :span="12">
                    <el-form-item label="Dates Range">
                        <el-date-picker
                                v-model="filters.filter_date"
                                type="daterange"
                                align="right"
                                unlink-panels
                                range-separator="-"
                                start-placeholder="Start date"
                                end-placeholder="End date"
                                :picker-options="datePickerOptions"
                                :clearable="false"
                                format="d/M/yyyy"
                                value-format="yyyy-MM-dd"
                                class="form-control input-sm">
                        </el-date-picker>
                    </el-form-item>
                </el-col>
                <el-col :span="6" :offset="6" class="text-right">
                    <el-button type="primary" @click="search" size="small"  icon="el-icon-search">Search</el-button>
                </el-col>


            </el-row>
        </el-form>
    </div>
</template>

<script>
    import moment from 'moment'

    export default {
        name: "SearchForm"
        , data() {
            return {
                filters:{
                    filter_transaction_id: ''
                    , filter_customer_id: ''
                    , filter_customer_name: ''
                    , filter_amount_operation: '='
                    , filter_amount: ''
                    , dates:''
                }
                , datePickerOptions: {
                    firstDayOfWeek: 1
                    , shortcuts: [
                        {
                            text: 'Today',
                            onClick(picker) {
                                picker.$emit('pick', [
                                    moment().toDate(),
                                    moment().toDate(),
                                ]);
                            }
                        },
                        {
                            text: 'this week',
                            onClick(picker) {
                                picker.$emit('pick', [
                                    moment().startOf('week').toDate(),
                                    moment().toDate(),
                                ]);
                            }
                        },
                        {
                            text: 'last week',
                            onClick(picker) {
                                picker.$emit('pick', [
                                    moment().subtract(1, 'week').startOf('week').toDate(),
                                    moment().subtract(1, 'week').endOf('week').toDate(),
                                ]);
                            }
                        },
                        {
                            text: 'This month',
                            onClick(picker) {
                                picker.$emit('pick', [
                                    moment().startOf('month').toDate(),
                                    moment().toDate(),
                                ]);
                            }
                        },

                        {
                            text: 'Last month',
                            onClick(picker) {
                                picker.$emit('pick', [
                                    moment().subtract(1, 'months').startOf('month').toDate(),
                                    moment().subtract(1, 'months').endOf('month').toDate(),
                                ]);
                            }
                        }
                    ]
                },
            }
        }
        , props: {
            customer_id:{
                type: Number
                , default: 0
            }
        }
        ,  methods : {
            // TODO: need to set disable for some inputs when set transaction_id or customer_id
            search(){
                let filters = {};
                if(this.filters.filter_transaction_id) {
                    filters.filter_transaction_id = this.filters.filter_transaction_id;
                    // return filters;
                }
                else if(this.filters.filter_customer_id) {
                    filters.filter_customer_id = this.filters.filter_customer_id;
                    if(this.filters.filter_amount) filters.filter_amount = this.filters.filter_amount;
                    if(this.filters.filter_amount) filters.filter_amount_operation = this.filters.filter_amount_operation;
                    if(this.filters.dates) filters.filter_date_from = this.filters.dates[0];
                    if(this.filters.dates) filters.filter_date_to = this.filters.dates[1];
                    // return filters;
                }
                else{
                    if(this.filters.filter_customer_name) filters.filter_customer_name = this.filters.filter_customer_name;
                    if(this.filters.filter_amount) filters.filter_amount = this.filters.filter_amount;
                    if(this.filters.filter_amount) filters.filter_amount_operation = this.filters.filter_amount_operation;
                    if(this.filters.dates) filters.filter_date_from = this.filters.dates[0];
                    if(this.filters.dates) filters.filter_date_to = this.filters.dates[1];
                    //this.$events.$emit('loan-filter-set', this.filledFilters);
                }
                this.$events.$emit('transactions-filter-set', filters);
            }
        }
    }
</script>

