<template>
    <div>
        <div class="container">
            <el-breadcrumb separator="/" style="margin: 20px; margin-left: 0px">
                <el-breadcrumb-item >Sentiment Analytics</el-breadcrumb-item>
                <el-breadcrumb-item><a href="/requests">Request</a></el-breadcrumb-item>
                <el-breadcrumb-item><a v-bind:href="link_detail + request_id">Request Processed</a></el-breadcrumb-item>
            </el-breadcrumb>
            <el-button @click="clearFilter">Reset all filters</el-button>
            <el-table
                    ref="filterTable"
                    :data="tableData"
                    style="width: 100%">
                <el-table-column type="expand">
                    <template slot-scope="scope">
                        <div style="margin-left: 10px">
                            <p>S3 URL: <a v-bind:href="scope.row.url">{{ scope.row.s3_uri}}</a></p>
                            <el-input
                                    style="width: 99%;"
                                    type="textarea"
                                    :autosize="{ minRows: 4, maxRows: 10}"
                                    v-model="scope.row.body">
                            </el-input>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column
                        prop="title"
                        label="Title"
                        width="400">
                </el-table-column>
                <el-table-column
                        label="Link"
                        width="150">
                    <template slot-scope="scope">
                        <a v-bind:href="scope.row.url">Link</a>
                    </template>
                </el-table-column>
                <el-table-column
                        prop="status"
                        label="Status"
                        width="100">
                    <template slot-scope="scope">
                        <el-tag
                                :type="scope.row.status === 'Pending' ? 'primary' : 'success'"
                                disable-transitions>{{scope.row.status}}</el-tag>
                    </template>
                </el-table-column>
                <el-table-column
                        prop="score"
                        label="Score"
                        width="100">
                </el-table-column>
                <el-table-column
                        prop="named_entity_matching"
                        label="Keyword checking"
                        width="200"
                        :filters="[{ text: 'Match', value: true }, { text: 'Not Match', value: false }]"
                        :filter-method="filterTag"
                        filter-placement="bottom-end">
                    <template slot-scope="scope">
                        <el-tag
                                :type="scope.row.named_entity_matching === 'true' ? 'primary' : 'success'"
                                disable-transitions>{{scope.row.named_entity_matching}}</el-tag>
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
                link_detail: "http://127.0.0.1:8080/requests/",
                tableData: [
                ]
            }
        },
        props: [
            'request_id'
        ],
        mounted() {
            this.fetchListProcessed();
        },
        methods: {
            resetDateFilter() {
                this.$refs.filterTable.clearFilter('date');
            },
            clearFilter() {
                this.$refs.filterTable.clearFilter();
            },
            formatter(row, column) {
                return row.address;
            },
            filterTag(value, row) {
                return row.named_entity_matching === value;
            },
            filterHandler(value, row, column) {
                const property = column['property'];
                return row[property] === value;
            },
            async fetchListProcessed(){
                const { data }  = await httpCommon.get(`requests/${this.request_id}/response/raw/details/`);
                console.log(data.results)
                data.results.forEach(item => {
                    var obj = {
                        id: item.id,
                        s3_uri: item.s3_uri,
                        title: item.details[0].metadata.title,
                        body: item.details[0].metadata.body,
                        url: item.details[0].discovered_url.url,
                        status: item.details[0].discovered_url.status,
                        score: item.details[0].discovered_url.score,
                        named_entity_matching: item.details[0].discovered_url.named_entity_matching,
                    };
                    this.tableData.push(obj);
                });
                console.log(this.tableData);
            }
        }
    }
</script>
