Vue.component('formitem',
    {
        props: {
            activities:{}
        },
        data() {
            return {
              form: {}
            }
        },
        created:function(){
            this.form = JSON.parse(this.activities);
        },
        methods: {
            onSubmit() {
              console.log('submit!');
            }
        },
        template:
        '<el-form ref="form" :model="form" label-width="80px">\
        <el-form-item label="活动名称">\
          <el-input v-model="form.title"></el-input>\
        </el-form-item>\
        <el-form-item label="开始时间">\
          <el-col :span="12">\
            <el-date-picker type="datetime" placeholder="开始时间" v-model="form.begintime" style="width: 100%;"></el-date-picker>\
          </el-col>\
        </el-form-item>\
        <el-form-item label="结束时间">\
          <el-col :span="12">\
            <el-date-picker type="datetime" placeholder="结束时间" v-model="form.endtime" style="width: 100%;"></el-date-picker>\
          </el-col>\
        </el-form-item>\
        <el-form-item v-for="(formitem,index) in form.formitems" label="选项"+index>\
            <el-input v-model="formitem.name" placeholder="请输入选项名"></el-input>\
        </el-form-item>\
        <el-form-item>\
            <el-col :span="8">\
            <el-button type="warning">添加选项</el-button>\
            </el-col>\
        </el-form-item>\
        <el-form-item>\
          <el-button type="success" @click="onSubmit">立即创建</el-button>\
          <el-button>取消</el-button>\
        </el-form-item>\
      </el-form>'
    }
)
