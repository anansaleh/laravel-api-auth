<template>
    <div class="customer-form"
         v-loading="spinner"
         element-loading-text="Loading data...">
        <el-form ref="customerForm"
                 :model="customer_form"
                 :rules="validationsRules"
                 label-width="120px">
            <el-form-item prop="name" label="Name">
                <el-input v-model="customer_form.name"></el-input>
            </el-form-item>
            <el-form-item prop="email" label="Email">
                <el-input v-model="customer_form.email"></el-input>
            </el-form-item>
            <el-form-item prop="phone" label="Phone">
                <el-input v-model="customer_form.phone"></el-input>
            </el-form-item>
            <el-form-item >
                <router-link to="/customers" class="btn">Cancel</router-link>
                <input type="button" value="Save" class="btn btn-primary" @click="save()">
            </el-form-item>
        </el-form>
    </div>
</template>

<script>

    export default {
        name: "customer-from"
        , data() {
            let validatePhone = function (rule, value, callback){
                if (value === '') {
                    callback(new Error('Please input customer phone'));
                } else if(value.length < 10){
                    callback(new Error('Must be at least 10 digits long'));
                }
                else {
                    //let phoneno = /^\+?([0-9]{2})\)?[-. ]?([0-9]{4})[-. ]?([0-9]{4})$/;
                    let phoneno = /^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,3})|(\(?\d{2,3}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$/;
                    if (value.match(phoneno)) {
                        callback();
                    } else {
                        callback(new Error('Please input current phone number'));
                    }
                }
            };
            let validateName = function (rule, value, callback){
                if (value === '') {
                    callback(new Error('Please input customer name'));
                } else if(value.length < 3){
                    callback(new Error('Customer Name must be at least 3 digits long'));
                }else {
                        callback();
                }
            };
            return {
                spinner: false

                , customer_form: {
                    name: '',
                    email: '',
                    phone: ''
                }
                , errors: null
                , validationsRules: {
                    name:[
                        {validator: validateName, trigger: 'blur'}
                    ]
                    , email:[
                        { required: true, message: 'Please input customer email address', trigger: 'blur' },
                        { type: 'email', message: 'Please input correct email address', trigger: ['blur', 'change'] }
                    ]
                    , phone:[
                        {validator: validatePhone, trigger: 'blur'}
                        // { required: true, message: 'Please input customer phone', trigger: 'blur' },
                        // { minLength: minLength(10), message: 'Please input customer phone', trigger: 'blur' }
                    ]
                }
            };
        }
        , props: {
            customer_id:{
                type: Number
                , default: 0
            }
        }
        , created() {
            this.loadData();
        }
        , methods: {
            loadData(){
                if (this.customer_id){
                    let self = this;
                    self.spinner=true;
                    axios.get('/api/customers/' + self.customer_id)
                        .then((response) => {
                            self.customer_form.name = response.data.name;
                            self.customer_form.email = response.data.email;
                            self.customer_form.phone = response.data.phone;
                            self.spinner=false;
                        }).catch(e => {
                            self.spinner = false;
                            self.$message({
                                type:'error'
                                , message:e.response.data.error.message
                            });
                        });
                }
            }
            , save(){
                let self = this;
                let url = (this.customer_id)? '/api/customers/edit/' + this.customer_id : '/api/customers/new';
                let data={
                    name: self.customer_form.name
                    , email: self.customer_form.email
                    , phone: self.customer_form.phone
                }
                this.$refs['customerForm'].validate(function(valid){
                    if (valid) {
                        self.spinner=true;
                        axios({
                            method:'POST'
                            , url: url
                            , data: data
                        })
                            .then(function(response) {
                                self.spinner = false;
                                self.$router.push('/customers');
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