<style>
    .customer-card {
        display: flex;
        align-items: center;
    }
    .customer-view .customer-img {
        flex: 1;
    }
    .customer-view .customer-img img {
        max-width: 160px;
    }
    .customer-view .customer-info {
        flex: 3;
        overflow-x: scroll;
    }
</style>

<template>
    <div class="customer-view" v-if="customer">
        <el-row :gutter="20">
            <router-link to="/customers">Back to all customers</router-link>
            <div class="customer-card" >
                <div class="customer-img">
                    <img src="https://www.scottsdaleazestateplanning.com/wp-content/uploads/2018/01/user.png" alt="">
                </div>
                <div class="customer-info">
                    <table class="table">
                        <tr>
                            <th>ID</th>
                            <td>{{ customer.customer_id }}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{ customer.name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ customer.email }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ customer.phone }}</td>
                        </tr>
                    </table>

                </div>
            </div>
        </el-row>
        <el-row :gutter="20">
            <h3>Transactions</h3>
        </el-row>
        <el-row :gutter="20">
            <transactions-list :customer_id="customer.customer_id"></transactions-list>
        </el-row>

    </div>

</template>

<script>
    import TransactionsList from '../transactions/List.vue'
    export default {
        name: "customer-view"
        , components: {
            TransactionsList
        }
        , data() {
            return {
                customer: null

            };
        }
        , created() {
            let self = this;
            axios.get('/api/customers/' + self.$route.params.id)
                .then((response) => {
                    self.customer = response.data
                });
        }
    }
</script>
