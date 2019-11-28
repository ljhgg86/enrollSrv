const activity = Vue.component('activity',
    {
        data() {
            return {
              form: {}
            }
        },
        created:function(){
            console.log("abc");
            console.log(this.$route.query.id);
        },
        methods: {
            onSubmit() {
              console.log('submit!');
            }
        },
        template:
        '<div>abcd</div>'
    }
)
