<template>
    <el-container>
        <el-header>{{ title }}</el-header>
        <el-main>
            <el-table :data="lists" style="width: 100%">
                <!-- <el-table-column
                    label="标识">
                    <template slot-scope="scope">
                        <span style="margin-left: 10px">{{ scope.row.id }}</span>
                    </template>
                </el-table-column> -->
                <el-table-column type="index" :index="indexMethod" width="80">
                </el-table-column>
                <el-table-column
                label="信息">
                <template slot-scope="scope">
                    <el-popover trigger="hover" placement="top">
                    <p v-for="(forminfo, index) in scope.row.info" :key="index">{{forminfo}}</p>
                    <div slot="reference" style="font-size:16px;">
                        {{ scope.row.info.join(',') }}
                    </div>
                    </el-popover>
                    <el-row>
                        <el-col :span="4" v-for="(media, index) in scope.row.media" :key="index">
                            <el-image
                                style="width: 100px; height: 100px"
                                :src="media"
                                :preview-src-list="scope.row.media">
                            </el-image>
                        </el-col>
                    </el-row>
                </template>
                </el-table-column>
                <el-table-column
                label="操作"
                width="100">
                <template slot-scope="scope">
                    <el-button v-if="scope.row.readflag" type="success" round  size="mini" @click="updateRead(scope)" >已审</el-button>
                    <el-button v-else type="info" round  size="mini"  @click="updateRead(scope)">未审</el-button>
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
                activityId:0,
                lists:[],
                total:0,
                per_page:15,
                title:'',
            }
        },
        created:function(){
            this.activityId = this.$route.query.id;
        },
        mounted:function(){
            this.loadEnrollinfos();
        },
        methods: {
            loadEnrollinfos(){
                let url = this.GLOBAL.srvUrl + "enrollinfos/getEnrollinfos/"+this.activityId;
                axios.get(url)
                .then(function(data){
                    let listsTemp = data.data.enrollinfos;
                    this.lists = listsTemp.infos;
                    this.total = parseInt(listsTemp.total);
                    this.per_page = parseInt(listsTemp.per_page);
                    this.title = listsTemp.title;
                }.bind(this))
                .catch(function(error){
                }.bind(this));
            },
            indexMethod(index){return index+1;},
            updateRead(scope){
                var index = scope.$index;
                this.lists[index].readflag = !(this.lists[index].readflag);
                let url = this.GLOBAL.srvUrl + "enrollinfos/updateReadflag/"+scope.row.id;
                axios.get(url)
                .then(function(data){
                }.bind(this))
                .catch(function(error){
                }.bind(this));
            },
        },
    }
</script>
