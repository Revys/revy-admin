<template>
    <div class="loader" :class="{ 'loader--visible': visible, 'loader--finish': finish }">
        <div class="loader__status"></div>
        <div class="loader__status-max" :class="resultClass"></div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                visible: false,
                finish: false,
                result: false
            }
        },

        mounted() {
            window.Loader = this;
        },

        computed: {
            resultClass: function () {
                if (this.result === false)
                    return false;

                return 'loader__status-max--' + this.result;
            }
        },

        methods: {
            show: function () {
                this.hide();
                this.visible = true;
            },
            hide: function () {
                this.visible = false;
                this.finish = false;
                this.result = false;
            },
            done: function (result) {
                this.finish = true;

                setTimeout(() => {
                    this.result = result;
                }, 150);

                setTimeout(() => {
                    this.hide()
                }, 1000);
            }
        }
    }
</script>