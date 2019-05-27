
<style scoped>

</style>

<template>
    <div class="customer-edit">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="el-icon-circle-plus"></i>
                    Add new Customer
                </h3>
            </div>
            <div class="panel-body">
                <customer-from></customer-from>
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
    }
</script>
