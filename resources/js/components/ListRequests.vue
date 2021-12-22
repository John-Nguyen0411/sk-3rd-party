<template>
     <div>
         <div class="container">
             <el-breadcrumb separator="/">
                 <el-breadcrumb-item >Sentiment Analytics</el-breadcrumb-item>
                 <el-breadcrumb-item><a href="/">Request</a></el-breadcrumb-item>
             </el-breadcrumb>

             <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="120px" class="search-form">
                 <el-form-item label="Keyword" prop="keyword">
                     <el-row :gutter="20">
                         <el-col :span="6">
                             <el-input v-model="ruleForm.keyword"></el-input>
                         </el-col>
                     </el-row>

                 </el-form-item>
                 <el-form-item label="Domain site" prop="type">
                     <el-checkbox-group v-model="ruleForm.type">
                         <el-checkbox label="RCMP" name="type"></el-checkbox>
                         <el-checkbox label="MONTREALPOLICE" name="type"></el-checkbox>
                         <el-checkbox label="CANLII" name="type"></el-checkbox>
                         <el-checkbox label="PEELPOLICE" name="type"></el-checkbox>
                     </el-checkbox-group>
                 </el-form-item>
                 <el-form-item>
                     <el-button type="primary" @click="submitForm('ruleForm')">Send</el-button>
                     <el-button @click="resetForm('ruleForm')">Reset</el-button>
                 </el-form-item>
             </el-form>
             <el-table
                     :data="tableData"
                     style="width: 100%"
                     max-height="500">
                 <el-table-column
                         fixed
                         prop="keyword"
                         label="Keyword"
                         width="150">
                 </el-table-column>
                 <el-table-column
                         prop="status"
                         label="Status"
                         width="120">
                 </el-table-column>
                 <el-table-column
                         prop="source_type"
                         label="Source type"
                         class="type-break"
                         width="480">
                 </el-table-column>
                 <el-table-column
                         prop="created_at"
                         label="Sent at"
                         width="120">
                 </el-table-column>
                 <el-table-column
                         fixed="right"
                         label="Operations"
                         prop="id"
                         width="120">
                     <template slot-scope="prop">
                         <a v-bind:href="link_detail + prop.row.id">Detail</a>
                     </template>
                 </el-table-column>
             </el-table>
         </div>

     </div>
</template>



<script>
    import httpCommon from "../repos/HttpCommon";

    export default {
        data() {
            return {
                input: "",
                link_detail: "http://127.0.0.1:8080/requests/",
                requests: null,
                multisite: "MULTISITE",
                ruleForm: {
                    name: '',
                    type: [],
                },
                sent_data: {
                    data: "",
                    source_type: "MULTISITE",
                },
                rules: {
                    keyword: [
                        { required: true, message: 'Please input Keyword', trigger: 'blur' },
                        { min: 3, max: 50, message: 'Length should be 3 to 5', trigger: 'blur' }
                    ],
                    type: [
                        { type: 'array', required: true, message: 'Please select at least one activity type', trigger: 'change' }
                    ]
                },
                tableData: []
            }
        },
        mounted(){
            this.fetchListQuests();
        },
        methods: {
            submitForm(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        console.log(this.ruleForm);
                        let data = this.convertDataToSent(this.ruleForm);
                        this.postRequest(data);
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            resetForm(formName) {
                this.$refs[formName].resetFields();
            },
            notifySuccess() {
                this.$message({
                    showClose: true,
                    message: 'Request was sent successfully',
                    type: 'success'
                });
            },
            async fetchListQuests(){
                const { data }  = await httpCommon.get('requests/');
                console.log(data);
                data.results.forEach(item => {
                    var obj = {
                        id: item.id,
                        keyword: item.data.keyword,
                        status: item.status,
                        source_type: item.data.source_type.join(", "),
                        created_at: item.created.split("T")[0]
                    };
                    this.tableData.push(obj);
                });
                console.log(this.tableData);
            },
            deleteRow(index, tabledata){
                console.log("****")
                console.log(index);

                console.log(tabledata);
            },
            convertDataToSent(data){
                if(data.type.length === 1){
                    this.sent_data.keyword = data.keyword;
                    this.sent_data.source_type = data.type[0];
                }else{
                    let obj = {
                        keyword: data.keyword,
                        source_type: data.type
                    }
                    this.sent_data.data = obj;
                }
                return JSON.stringify(this.sent_data);
            },
            postRequest(data) {
                console.log(data);
                return new Promise((resolve, reject) => {
                    httpCommon.post('requests/', data)
                        .then((res) => {
                            resolve(res.data);
                            this.fetchListQuests();
                            this.notifySuccess();
                        })
                        .catch(errors => {
                           reject(errors.response)
                            console.log(errors.response)
                        })
                })
            },
        }
    }
</script>

<style scoped>
    .container{
        font-size: 15px !important;
    }
    .search-form{
        margin: 10px;
    }
    .el-table_1_column_3 div{
        word-break: break-word !important;
        color: red;
    }
    .el-breadcrumb,.el-button--mini, .el-button--small, .el-table{
        font-size: 15px !important;
    }
</style>