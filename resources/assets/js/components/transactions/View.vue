<template>
    <div class="transaction-view">
        <div class="transaction-card" >
            <el-card class="box-card">
                <div slot="header" class="clearfix">
                    <span>Transaction</span>
                </div>
                <div class="text item"
                     v-loading="spinner"
                     element-loading-text="Loading data...">
                    <table class="table" v-if="transaction">
                        <tr>
                            <th>ID</th>
                            <td>{{ transaction.transaction_id }}</td>
                        </tr>
                        <tr>
                            <th>Customer ID</th>
                            <td>{{ transaction.customer_id }}</td>
                        </tr>
                        <tr>
                            <th>Customer name</th>
                            <td>{{ transaction.customer_name }}</td>
                        </tr>
                        <tr>
                            <th>Amount</th>
                            <td>{{ transaction.amount }}</td>
                        </tr>
                        <tr>
                            <th>Created at</th>
                            <td>{{ transaction.created_at }}</td>
                        </tr>
                        <tr>
                            <th>Updated at</th>
                            <td>{{ transaction.updated_at }}</td>
                        </tr>
                    </table>
                    <div v-else>
                        No Data found
                    </div>
                </div>
            </el-card>
        </div>
    </div>
</template>

<script>
    export default {
        name: "transaction-view"
        , data() {
            return {
                transaction: null
                , spinner: false
            };
        }
        , props: {
            transaction_id:{
                type: Number
                , required: true
            }
            , customer_id:{
                type: Number
                , default: 0
            }
        }
        , created() {
            this.init();
        }
        , methods: {
            init(){
                let url = (this.customer_id)? '/api/customers/' + this.customer_id + '/transactions/' + this.transaction_id
                                             : '/api/transactions/' + this.transaction_id;

                let self = this;
                self.spinner = true;
                axios.get(url)
                    .then((response) => {
                        self.transaction = response.data.data;
                        self.spinner = false;
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
        }
    }
</script>

<style scoped>

</style>