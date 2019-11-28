<template>
    <el-container>
        <el-main>
        <el-row type="flex" justify="end">
            <el-button  type="success" plain round icon="el-icon-plus">
                新建活动
            </el-button>
        </el-row>
        <el-table
        :data="lists.filter(data => !search || data.name.toLowerCase().includes(search.toLowerCase()))"
        border
        stripe
        style="width: 100%">
            <el-table-column
                type="index"
                label="序号"
                width="60"
                align="center">
            </el-table-column>
            <el-table-column
                prop="title"
                label="活动名称"
                align="center">
            </el-table-column>
            <el-table-column
                label="操作"
                width="150"
                align="center">
                <template slot-scope="scope">
                    <el-button
                    size="mini"
                    type="success"
                    @click="handleCheck(scope.$index, scope.row)">查看</el-button>
                    <el-button
                    size="mini"
                    @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
                </template>
            </el-table-column>
        </el-table>
        </el-main>
        <el-footer>
        <el-pagination
        background
        layout="prev, pager, next"
        :hide-on-single-page=true
        :page-size="this.per_page"
        :total=this.total>
        </el-pagination>
        </el-footer>
        </el-container>
</template>

<script>
    export default {
        data() {
            return {
                activities:{},
                lists:[],
                search: '',
                total:0,
                per_page:15,
            }
        },
        created:function(){
            // let listsTemp = JSON.parse(this.activities);
            // this.lists = listsTemp.data;
            // this.total = parseInt(listsTemp.total);
            // this.per_page = parseInt(listsTemp.per_page);
            this.handleLoad();
        },
        methods: {
            handleLoad() {
                let url = "http://127.0.0.1:8000/api/activities/list";
                axios.get(url)
                .then(function(data){
                    let listsTemp = data.data;
                    this.lists = listsTemp.data;
                    this.total = parseInt(listsTemp.total);
                    this.per_page = parseInt(listsTemp.per_page);
                }.bind(this))
                .catch(function(error){
                }.bind(this));
            },
            handleEdit(index, row) {
                // console.log(index);
                // console.log(row.id);
                // console.log(this.$router);
                //this.$store.commit('setActivityId', row.id);
                this.$router.push({path:'/activity', query:{id: row.id}});
            },
            handleCheck(index, row) {
                console.log(index, row);
            }
        },
    }
</script>
