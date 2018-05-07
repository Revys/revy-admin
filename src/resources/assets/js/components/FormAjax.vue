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

                var data = new FormData();

                for(var index in vm.form.data()) {
                    data.append(index, vm.form[index]);
                }

                $("#" + vm.formId).find('input[type=file]').each(function (i, el) {
                    for (var i = 0; i < el.files.length; i++) {
                        data.append(el.name + (el.files.length > 1 ? '[]' : ''), el.files[i], el.files[i].name);
                    }
                });

                $.request({
                    type: 'POST',
                    url: vm.formEl.getAttribute('action'),
                    data: data,
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