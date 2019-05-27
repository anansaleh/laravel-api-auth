<template>
    <div class="transaction-from">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="el-icon-edit"></i>
                    {{form_title}}
                </h3>
            </div>
            <div class="panel-body"
                 v-loading="spinner"
                 element-loading-text="Loading data...">
                <el-form ref="transactionForm"
                         :model="transaction_fields"
                         :rules="validationsRules"
                         label-width="120px">

                    <div class="customer-info" v-if="customer_id || transaction_id">
                        <table class="table">
                            <tr>
                                <th>Customer ID</th>
                                <td>{{ transaction_fields.customer_id }}</td>
                            </tr>
                            <tr>
                                <th>Customer Name</th>
                                <td>{{ transaction_fields.customer_name }}</td>
                            </tr>
                        </table>
                        <el-divider></el-divider>
                    </div>
                    <el-form-item v-else label="Customer" prop="customer">
                        <el-select v-model="transaction_fields.customer_id" filterable placeholder="Select" >
                            <el-option
                                    v-for="item in customers_list"
                                    :key="item.customer_id"
                                    :label="item.name"
                                    :value="item.customer_id">
                            </el-option>
                            <template slot="prepend"><i class="el-icon-s-custom"></i></template>
                        </el-select>
                    </el-form-item>

                    <el-form-item label="Amount" prop="amount">
                        <el-input v-model="transaction_fields.amount">
                            <template slot="prepend">£</template>
                        </el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="save">Save</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: "transaction-from"
        , data(){
            let vm = this;
            let validateAmount = function (rule, value, callback){
                if (value === '') {
                    callback(new Error('Please input Amount'));
                } else {
                    if (Number.isNaN( Number.parseFloat(value) )) {
                        callback(new Error('Please input number'));
                    } else if (parseFloat(value) <= 0) {
                        callback(new Error('Amount must be greater than 0'));
                    } else {
                        callback();
                    }
                }
            };
            return {
                spinner: false
                , customers_list:[]
                //, customer_name:''
                , transaction_fields:{
                    transaction_id: ''
                    , customer_id: ''
                    , customer_name:''
                    , amount: 0
                }
                , validationsRules: {
                    customer_id:[
                        { customer_id: true, message: 'Please select customer', trigger: 'change' }
                    ]
                    , amount: [
                        {validator: validateAmount, trigger: 'blur'}
                    ]
                }
            }
        }
        , props: {
            transaction_id:{
                type: Number
                , default: 0
            }
            , customer_id:{
                type: Number
                , default: 0
            }
        }
        , computed:{
            form_title:function(){
                return (this.transaction_id === 0 )? 'New Transaction': 'Edit Transaction: '+ this.transaction_id;
            }
            , customer_name:function(){
                let self = this;
                let found = this.customers_list.find(function(element) {
                    return element.customer_id == self.customer_id;
                });
                if(found){
                    self.transaction_fields.customer_id= self.customer_id;
                    return found.name;
                }else{
                  return '';
                }
            }
        }

        , created() {
            this.init();
        }
        , mounted: function () {
            var self =this;
            this.$nextTick(function () {
               // self.init();
            })
        }
        , methods: {
            init(){
                let self = this;
                self.spinner = true;
                //let url = (self.customer_id)? '/api/customers/'+ self.customer_id : '/api/customers/list'
                let url = '/api/customers/list';
                axios.get(url)
                    .then((response) => {
                        self.customers_list = response.data;
                        if (self.customer_id){
                            self.transaction_fields.customer_id = self.customer_id;
                            self.transaction_fields.customer_name = self.customer_name;
                        }
                        if(self.transaction_id > 0){
                            self.loadData(self.transaction_id);
                        }else{
                            self.spinner = false;
                        }
                    })
                    .catch(e => {
                        console.log(e);
                        self.spinner = false;
                        self.$message({
                            type:'error'
                            , message:e.response.data.error.message
                        });

                    });
            }
            , loadData(transaction_id){
                var self = this;
                self.spinner = true;
                axios.get('/api/transactions/'+ transaction_id)
                .then(function(response){
                    self.transaction_fields.transaction_id = transaction_id;
                    self.transaction_fields.customer_id = response.data.data.customer_id;
                    self.transaction_fields.customer_name = response.data.data.customer_name;
                    self.transaction_fields.amount = response.data.data.amount;
                    self.spinner = false;
                })
                .catch(e => {
                    self.spinner = false;
                    self.$message({
                        type:'error'
                        , message:e.response.data.error.message
                    });
                });
            }
            , save(){
                let url = '';
                let data={};
                if (this.transaction_id){
                    url = '/api/customers/' + this.transaction_fields.customer_id + '/transactions/update';
                    data={
                        transaction_id: this.transaction_fields.transaction_id
                        , amount:this.transaction_fields.amount
                    }
                }else{
                    url = '/api/customers/' + this.transaction_fields.customer_id + '/transactions/add';
                    data={
                        amount:this.transaction_fields.amount
                    }
                }
                let self = this;
                this.$refs['transactionForm'].validate(function(valid){
                    if (valid) {
                        self.spinner=true;
                        axios({
                            method:'POST'
                            , url: url
                            , data: data
                        })
                            .then(function(response) {
                                // console.log(response.data);
                                // self.pp_items = response.data;
                                self.transaction_fields.transaction_id = response.data.transaction_id;
                                self.transaction_fields.customer_id = response.data.customer_id;
                                self.transaction_fields.amount = response.data.amount;
                                self.spinner = false;

                                // target_item.id: This only to know if it for add_new or update
                                self.$emit('successSaveTransaction');
                            })
                            .catch(e => {
                                self.spinner = false;
                                self.$message({
                                    type:'error'
                                    , message:e.response.data.error.message
                                });
                            });

                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });

            }
        }
    }
</script>

<style scoped>

</style>