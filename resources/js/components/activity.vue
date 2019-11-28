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
                      end-placeholder="结束日期">
                    </el-date-picker>
                </el-form-item>
                <el-form-item label="封面图片">
                    <el-row>
                        <el-col :span="12">
                            <el-image
                            v-if="topimg"
                            :src="topimg">
                            </el-image>
                        </el-col>
                     </el-row>
                     <el-row>
                        <el-upload
                        style="color: transparent;"
                        action="http://127.0.0.1:8000/api/images/uploadImgFileApi"
                        :limit="1"
                        :file-list="filelist"
                        :show-file-list=false
                        :before-upload="checkUpload"
                        :on-success="uploadSuccess">
                        </el-upload>
                      </el-row>
                </el-form-item>
                <el-divider><span style="color:#409EFF;">更多信息</span></el-divider>
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
                    <el-row :gutter="10" v-for="(childitem,cindex) in formitem.childformitems" :key="cindex" style="padding:5px 0">
                        <el-col :span="2" :offset="2">子项{{ cindex+1 }}</el-col>
                        <el-col :span="10">
                            <el-input v-model="childitem.name"></el-input>
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
                <el-form-item>
                    <el-button type="primary" @click="onSubmit">提交</el-button>
                    <el-button>取消</el-button>
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
                topimg:"",
                tagOptions:[],
                multiOptionTypes:['radio','checkbox','select'],
                //dialog var
                dialogVisible:false,
                childFormitemDialog:false,
                formitemMoveDirect:"1",
                formitemMoveLines:0,
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
        },
        methods: {
            //加载数据
            handleLoad() {
                let url = "http://127.0.0.1:8000/api/activities/getActivity/"+this.activityId;
                axios.get(url)
                .then(function(data){
                    this.topimg = "http://127.0.0.1:8000/uploads/"+data.data.activity.topimg;
                    this.pushActivity(data.data.activity);
                    this.pushFormtags(data.data.formtags);
                }.bind(this))
                .catch(function(error){
                }.bind(this));
            },
            //规范数据格式
            pushActivity(activity){
                this.activity.id = activity.id;
                this.activity.title = activity.title;
                this.activity.topimg = activity.topimg;
                this.activity.begintime = activity.begintime;
                this.activity.endtime = activity.endtime;
                for(var i in activity.formitems){
                    let childFormitemTemp = [];
                    for(var j in formitems[i].childformitems){
                        childFormitemTemp.push({
                            id:formitems[i].childformitems[j].id,
                            name:formitems[i].childformitems[j].name,
                            order:formitems[i].childformitems[j].order,
                            defaultflag:formitems[i].childformitems[j].defaultflag
                        });
                    }
                    this.activity.formitems.push({
                        name:activity.formitems[i].name,
                        activities_id:activity.formitems[i].activities_id,
                        formtagname:activity.formitems[i].formtagname,
                        need:activity.formitems[i].need,
                        order:activity.formitems[i].order,
                        showAddChildBtn:this.multiOptionTypes.indexOf(activity.formitems[i].formtagname)>-1,
                        childformitems:childitemTemp
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
                    name:'',
                    activities_id:this.activityId,
                    formtag_id:null,
                    formtagname:'',
                    need:0,
                    order:this.activity.formitems.length + 1,
                    showAddChildBtn:false,
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
                    name:'',
                    formitem_id:index,
                    order:this.activity.formitems[index].childformitems.length + 1
                };
                this.activity.formitems[index].childformitems.push(childitemTemp);
            },
            //删除子项
            delChilditem(index, cindex){
                this.activity.formitems[index].childformitems.splice(cindex,1);
            },
            //根据选择输入类型显示添加子项按钮与否
            handleChange(formitemName,index){
                let intersection = this.multiOptionTypes.filter(function(val){
                    return formitemName.indexOf(val) > -1;
                });
                this.activity.formitems[index].showAddChildBtn = (intersection.length > 0) ? true : false;
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
                this.moveMax = this.activity.formitems[index].length - cindex - 1;
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
            checkUpload(){},
            uploadSuccess(res, file){
                console.log("res"+res);
                console.log("file:"+file);
            },
            onSubmit() {
              console.log('submit!');
            }
        },
    }
</script>
