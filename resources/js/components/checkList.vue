<template>
    <el-container>
        <el-main>
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
                lists:[],
                search: '',
                total:0,
                per_page:15,
            }
        },
        created:function(){
            this.handleLoad();
        },
        methods: {
            handleLoad() {
                let url = this.GLOBAL.srvUrl + "activities/list";
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
            handleCheck(index, row) {
                this.$router.push({path:'/checkactivitylist', query:{id: row.id}});
            }
        },
    }
</script>
