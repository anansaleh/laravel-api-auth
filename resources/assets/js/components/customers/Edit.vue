
<style scoped>

</style>

<template>
    <div class="customer-edit">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="el-icon-edit"></i>
                    Edit Customer: {{customer_id}}
                </h3>
            </div>
            <div class="panel-body">
                <customer-from :customer_id="customer_id"></customer-from>
            </div>
        </div>

    </div>
</template>

<script>
    import CustomerFrom from './Form.vue'
    export default {
        name: "customer-new"
        , components: {
            CustomerFrom
        }
        , data() {
            return {
                // customer: {
                //     name: '',
                //     email: '',
                //     phone: ''
                // },
                // errors: null
                customer_id: ''
            };
        }
        , created() {
            this.init();
        }
        , methods:{
            init(){
                this.customer_id = parseInt(this.$route.params.id);
            }
        }
    }
</script>
