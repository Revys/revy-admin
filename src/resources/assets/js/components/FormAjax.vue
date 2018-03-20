<script>
    import Form from "../core/Form";

    export default {
        props: [
            'form-id'
        ],

        data() {
            return {
                form: null
            }
        },

        created() {
            let serialized = $('#' + this.formId).serializeArray();
            let data = {};

            $.map(serialized, function(n, i){
                data[n.name] = n.value;
            });

            this.form = new Form(data);
        },

        mounted() {

        },

        methods: {
            submit() {
                let vm = this;

                $.request({
                    url: vm.$el.action,
                    data: vm.form.data(),
                    success(data) {
                        vm.form.set(data);
                        vm.form.errors.clear();
                    },
                    error(data) {
                        vm.form.onFail(data.errors);
                    }
                });
            }
        }
    }
</script>