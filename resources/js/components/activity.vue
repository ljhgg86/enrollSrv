<template>
    <el-container>
        <el-main>
            <el-form ref="form" :model="activity" label-width="80px">
                <el-divider><span style="color:#409EFF;">基本信息</span></el-divider>
                <el-form-item label="活动名称">
                    <el-input v-model="activity.title" style="width:50%;"></el-input>
                </el-form-item>
                <el-form-item label="活动时间">
                    <el-date-picker
                     v-model = "datetimeVal"
                      type="datetimerange"
                      range-separator="至"
                      start-placeholder="开始日期"
                      end-placeholder="结束日期"
                      @change="datetimeChange">
                    </el-date-picker>
                </el-form-item>
                <el-form-item label="封面图片">
                    <el-row v-if="activity.topimg">
                        <el-col :span="12">
                            <el-image
                            :src="activity.topimg">
                            </el-image>
                        </el-col>
                     </el-row>
                     <el-row v-if="activity.topimg">
                         <el-col :span="12">
                             <el-button
                             plain
                             size="mini"
                             type="danger"
                             icon="el-icon-delete"
                             style="width:100%"
                             @click="delTopimg">删除</el-button>
                         </el-col>
                     </el-row>
                     <el-row>
                        <el-upload
                        style="color: transparent;"
                        action="http://127.0.0.1:8000/api/images/uploadImgFileApi"
                        :file-list="filelist"
                        :show-file-list=false
                        :on-success="uploadSuccess">
                        </el-upload>
                      </el-row>
                </el-form-item>
                <el-collapse accordion>
                    <el-collapse-item>
                    <template slot="title">
                    <el-divider>
                        <span style="color:#409EFF;"><i class="el-icon-thumb"></i>更多选项信息<i class="el-icon-edit-outline"></i></span>
                    </el-divider>
                    </template>
                    <el-form-item v-for="(formitem,index) in activity.formitems" :key="index" :label="'选项'+ (index+1)">
                        <el-row :gutter="10">
                            <el-col :span="13">
                                <el-input v-model="formitem.name"></el-input>
                            </el-col>
                            <el-col :span="3">
                                <el-cascader
                                    v-model="formitem.formtagname"
                                    :options="tagOptions"
                                    :show-all-levels="false"
                                    @change="handleChange(formitem.formtagname, index)"></el-cascader>
                            </el-col>
                            <el-col :span="2">
                                <el-select v-model="formitem.need">
                                    <el-option label="选填" :value=0></el-option>
                                    <el-option label="必填" :value=1></el-option>
                                </el-select>
                            </el-col>
                            <el-col :span="1.5">
                                <el-button  @click="handleOrder(index)">排序</el-button>
                            </el-col>
                            <el-col :span="1.5">
                                <el-button type="danger" @click="delFormitem(index)">删除</el-button>
                            </el-col>
                            <el-col :span="2.5">
                                <el-button
                                type="warning"
                                style="width:100%;"
                                v-if="formitem.showAddChildBtn"
                                @click="addChilditem(index)">
                                添加子项
                                </el-button>
                            </el-col>
                        </el-row>
                        <el-row
                         :gutter="10"
                         v-if="formitem.showMediaOption">
                            <el-checkbox v-model="formitem.mediaOptions[0]">图片</el-checkbox>
                            <el-checkbox v-model="formitem.mediaOptions[1]">视频</el-checkbox>
                            <el-checkbox v-model="formitem.mediaOptions[2]">音频</el-checkbox>
                            <el-divider direction="vertical"></el-divider>
                            <span style="color:#606266;">最大上传数量</span>
                            <el-input-number v-model="formitem.mediaCount" @change="mediaCountChange" :min="0" :max="10"></el-input-number>
                         </el-row>
                        <el-row :gutter="10" v-for="(childitem,cindex) in formitem.childformitems" :key="cindex" style="padding:5px 0">
                            <el-col :span="2" :offset="2">子项{{ cindex+1 }}</el-col>
                            <el-col :span="10">
                                <el-input v-model="childitem.name"></el-input>
                            </el-col>
                            <el-col :span="1.5">
                                <el-checkbox v-model="childitem.defaultflag" :checked="childitem.defaultflag ? true : false">
                                    默选
                                </el-checkbox>
                            </el-col>
                            <el-col :span="1.5">
                                <el-button size="small" @click="handleChildOrder(index,cindex)">排序</el-button>
                            </el-col>
                            <el-col :span="2">
                                <el-button type="danger" size="small" @click="delChilditem(index,cindex)">删除</el-button>
                            </el-col>
                        </el-row>
                    </el-form-item>
                    <el-row type="flex" justify="center">
                        <el-col :span="22">
                            <el-button
                            type="warning"
                            icon="el-icon-plus"
                            round
                            style="width:100%;"
                            @click="addFormitem">
                                添加选项
                            </el-button>
                        </el-col>
                    </el-row>
                    <el-divider></el-divider>
                    </el-collapse-item>
                </el-collapse>
                <el-form-item>
                    <el-button type="primary" @click="onSubmit">提交</el-button>
                    <el-button @click="onCancle">取消</el-button>
                </el-form-item>`
            </el-form>
        </el-main>
        <el-dialog
        title="排序"
        :visible.sync="dialogVisible"
        width="30%">
            <el-row type="flex" justify="center" style="padding:10px 0;">
                <el-radio-group v-model="formitemMoveDirect" @change="moveDirectChange">
                    <el-radio label='2'>上移</el-radio>
                    <el-radio label='1'>下移</el-radio>
                </el-radio-group>
            </el-row>
            <el-row type="flex" justify="center">
                <el-input-number v-model="formitemMoveLines" :min=0 :max="moveMax"></el-input-number>
            </el-row>
            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">取 消</el-button>
                <el-button type="primary" @click="handleFormitemMove">确 定</el-button>
            </span>
        </el-dialog>
    </el-container>
</template>

<script>
    export default {
        data() {
            return {
                activityId:0,
                activity:{
                    id:0,
                    title:"",
                    topimg:"",
                    begintime:"",
                    endtime:"",
                    formitems:[],
                },
                datetimeVal:[new Date(), new Date()],
                filelist:[],
                //topimg:"",
                tagOptions:[],
                multiOptionTypes:['radio','checkbox','select'],
                //dialog var
                dialogVisible:false,
                childFormitemDialog:false,
                formitemMoveDirect:"1",//1向下
                formitemMoveLines:0,//移动行数
                activityLine:0,
                parentsLine:0,//子项排序时需要
                moveMax:0,
            }
        },
        created:function(){
            this.activityId = this.$route.query.id;
        },
        mounted:function(){
            if(this.activityId){
                this.handleLoad();
            }
            else{
                this.loadFormtags();
            }
        },
        methods: {
            //加载数据
            handleLoad() {
                let url = this.GLOBAL.srvUrl + "activities/getActivity/"+this.activityId;
                axios.get(url)
                .then(function(data){
                    this.pushActivity(data.data.activity);
                    this.pushFormtags(data.data.formtags);
                }.bind(this))
                .catch(function(error){
                }.bind(this));
            },

            //加载标签类型列表
            loadFormtags() {
                let url = this.GLOBAL.srvUrl + "activities/getFormtags";
                axios.get(url)
                .then(function(data){
                    this.pushFormtags(data.data.formtags);
                }.bind(this))
                .catch(function(error){
                }.bind(this));
            },

            //规范数据格式
            pushActivity(activity){
                let that = this;
                this.activity.id = activity.id;
                this.activity.title = activity.title;
                this.activity.topimg = activity.topimg;
                this.datetimeVal = [new Date(activity.begintime), new Date(activity.endtime)];
                // this.activity.begintime = this.datetimeVal[0];
                // this.activity.endtime = this.datetimeVal[1];
                for(var i in activity.formitems){
                    let childFormitemTemp = [];
                    for(var j in activity.formitems[i].childformitems){
                        childFormitemTemp.push({
                            id:activity.formitems[i].childformitems[j].id,
                            name:activity.formitems[i].childformitems[j].name,
                            order:activity.formitems[i].childformitems[j].order,
                            defaultflag:activity.formitems[i].childformitems[j].defaultflag
                        });
                    }
                    var formtagnameArr = (activity.formitems[i].formtagname).split(",");
                    var mediaEx = (activity.formitems[i].media_ex).split(",");
                    var mediaCount = parseInt(mediaEx.splice(-1)[0]);
                    for(var k=0;k<mediaEx.length;k++){
                        mediaEx[k] = mediaEx[k]=="1" ? true : false;
                    }
                    this.activity.formitems.push({
                        id:activity.formitems[i].id,
                        name:activity.formitems[i].name,
                        activities_id:activity.formitems[i].activities_id,
                        formtagname:activity.formitems[i].formtagname.split(","),
                        need:activity.formitems[i].need,
                        order:activity.formitems[i].order,
                        mediaCount:mediaCount,
                        mediaOptions:mediaEx,
                        showAddChildBtn:that.multiOptionTypes.indexOf(((activity.formitems[i].formtagname).split(",")).slice(-1)[0])>-1,
                        showMediaOption:(activity.formitems[i].formtagname.split(",")).indexOf("media")>-1,
                        childformitems:childFormitemTemp
                    });

                }
            },
            //规范选择输入类型格式
            pushFormtags(formtags){
                for(var i in formtags){
                    let inputTypes = new Array();
                    for(var j in formtags[i].inputtypes){
                        inputTypes.push({
                            value:formtags[i].inputtypes[j].name,
                            label:formtags[i].inputtypes[j].tag
                        });
                    }
                    let tagOption = (inputTypes.length>0) ? {value:formtags[i].name,label:formtags[i].tag,children:inputTypes} : {value:formtags[i].name,label:formtags[i].tag};
                    this.tagOptions.push(tagOption);
                }
            },
            //添加选项
            addFormitem(){
                var formitemTemp = {
                    id:0,
                    name:'',
                    activities_id:this.activityId,
                    formtag_id:null,
                    formtagname:'',
                    need:0,
                    order:this.activity.formitems.length + 1,
                    showAddChildBtn:false,
                    showMediaOption:false,
                    mediaCount:0,//数量
                    mediaOptions:[0,0,0],//图片,视频,音频
                    childformitems:[]
                };
                this.activity.formitems.push(formitemTemp);
            },
            //删除选项
            delFormitem(index){
                this.activity.formitems.splice(index,1);
            },
            //添加子项
            addChilditem(index){
                var childitemTemp = {
                    id:0,
                    name:'',
                    formitem_id:index,
                    order:this.activity.formitems[index].childformitems.length + 1,
                    defaultflag:0
                };
                this.activity.formitems[index].childformitems.push(childitemTemp);
            },
            //删除子项
            delChilditem(index, cindex){
                this.activity.formitems[index].childformitems.splice(cindex,1);
            },
            //根据选择输入类型显示添加子项按钮与否
            handleChange(formitemName,index){console.log(formitemName);
                let intersection = this.multiOptionTypes.filter(function(val){
                    return formitemName.indexOf(val) > -1;
                });
                this.activity.formitems[index].showAddChildBtn = (intersection.length > 0) ? true : false;
                this.activity.formitems[index].showMediaOption = formitemName.indexOf("media") > -1;
            },
            //排序对话框函数
            //1.1、初始化并显示选项排序对话框
            handleOrder(index){
                this.childFormitemDialog = false,
                this.formitemMoveDirect = "1";
                this.formitemMoveLines = 0;
                this.activityLine = index;
                this.moveMax = this.activity.formitems.length - index - 1;
                this.dialogVisible = true;
            },
            //1.2\初始化并显示子项排序对话框
            handleChildOrder(index,cindex){
                this.childFormitemDialog = true,
                this.formitemMoveDirect = "1";
                this.formitemMoveLines = 0;
                this.activityLine = cindex;
                this.parentsLine = index;
                this.moveMax = this.activity.formitems[index].childformitems.length - cindex - 1;
                this.dialogVisible = true;
            },
            //2、排序上移下移切换
            moveDirectChange(){
                this.formitemMoveLines = 0;
                let len = 0;
                if(this.childFormitemDialog){
                    len = this.activity.formitems[this.parentsLine].childformitems.length;
                }
                else{
                    len = this.activity.formitems.length;
                }
                if(this.formitemMoveDirect=="1"){
                    this.moveMax = len - this.activityLine - 1;
                }
                else{
                    this.moveMax = this.activityLine;
                }
            },
            //3、排序调整
            handleFormitemMove(){
                if(this.formitemMoveLines){
                    if(this.childFormitemDialog){
                        let childitemLine = this.activity.formitems[this.parentsLine].childformitems[this.activityLine];
                        this.activity.formitems[this.parentsLine].childformitems.splice(this.activityLine,1);
                        if(this.formitemMoveDirect == "1"){
                            this.activity.formitems[this.parentsLine].childformitems.splice((this.activityLine + this.formitemMoveLines),0,childitemLine);
                        }
                        else{
                            this.activity.formitems[this.parentsLine].childformitems.splice((this.activityLine - this.formitemMoveLines),0,childitemLine);
                        }
                    }
                    else{
                        let formitemLine = this.activity.formitems[this.activityLine];
                        this.activity.formitems.splice(this.activityLine,1);
                        if(this.formitemMoveDirect == "1"){
                            this.activity.formitems.splice((this.activityLine + this.formitemMoveLines),0,formitemLine);
                        }
                        else{
                            this.activity.formitems.splice((this.activityLine - this.formitemMoveLines),0,formitemLine);
                        }
                    }
                }
                this.dialogVisible = false;
            },
            //开始和结束时间确定
            datetimeChange(){
                // this.activity.begintime = this.datetimeVal[0];
                // this.activity.endtime = this.datetimeVal[1];
            },
            //媒体数量
            mediaCountChange(){

            },
            //封面图片上传成功返回
            uploadSuccess(res){
                this.activity.topimg = res.imageSrcs;
            },
            //删除封面图片
            delTopimg(){
                this.activity.topimg = "";
            },
            //提交数据
            onSubmit() {
                this.activity.begintime = this.datetimeVal[0];
                this.activity.endtime = this.datetimeVal[1];
                let url = this.GLOBAL.srvUrl + "activities/storeActivity";
                let that = this;
                axios.post(url,this.activity)
                .then(function(data){
                    that.$router.push({path:'/'});
                    that.$message({
                        showClose: true,
                        message:"活动操作成功！",
                        type:'success'
                    });
                }.bind(this))
                .catch(function(error){
                }.bind(this));
            },
            //取消
            onCancle(){
                this.$router.go(-1);
            }
        },
    }
</script>
