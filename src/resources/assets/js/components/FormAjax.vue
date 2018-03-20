<script>
    import Form from "../core/Form";

    export default {
        props: [
            'form-id'
        ],

        data() {
            return {
                form: null,
                formEl: null
            }
        },

        created() {
            this.formEl = document.getElementById(this.formId);

            let serialized = $(this.formEl).serializeArray();
            let data = {};

            $.map(serialized, function(n, i){
                data[n.name] = n.value;
            });

            this.form = new Form(data);
        },

        methods: {
            submit() {
                let vm = this;

                $.request({
                    url: vm.formEl.action,
                    data: vm.form.data(),
                    success(data) {
                        vm.form.set(data);
                        vm.form.errors.clear();
                    },
                    error(data) {
                        vm.form.errors.clear();
                        vm.form.onFail(data.errors);
                    }
                });
            }
        }
    }
</script>