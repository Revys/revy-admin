<template>
    <div>
        <il-image v-for="(image, index) in items" :key="image.id" :data="image" :index="index" @remove="remove"/>
    </div>
</template>

<script>
    import Image from './Image.vue';
    // import draggable from 'vuedraggable'

    export default {
        components: {
            'il-image': Image
            // draggable
        },

        props: [
            'images'
        ],

        data() {
            return {
                items: []
            }
        },

        mounted() {
            this.items = this.images;
        },

        methods: {
            remove: function (index) {
                var vm = this;

                $.request({
                    controller: 'service',
                    action: 'remove_image',
                    data: {
                        object_id: this.items[index].object_id,
                        filename: this.items[index].filename
                    },
                    complete: function() {
                        vm.items.splice(index, 1);
                        Alerts.add('Удалено', 'success');
                    }
                });
            }
        }
    }
</script>