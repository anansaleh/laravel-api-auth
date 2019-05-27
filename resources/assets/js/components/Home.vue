<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Home page</div>

                    <div class="card-body">
                        <h2>Welcome User: {{ currentUser.name }}</h2>
                        <p>This is home page for login user</p>
                    </div>
                </div>
            </div>
        </div>
        <transactions-sum></transactions-sum>
    </div>
</template>

<script>
    import TransactionsSum from './transactions/Sum.vue';
    export default {
        name: "home"
        , components: {
            TransactionsSum
        }
        , computed: {
            currentUser() {
                return this.$store.getters.currentUser
            }
        }
    }
</script>

<style scoped>

</style>