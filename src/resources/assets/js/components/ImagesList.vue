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
                this.items.splice(index, 1);

                // @todo Send remove image request

                $.request({
                    controller: 'services',
                    action: 'remove_image',
                    data: {
                        id: this.items[index-1].id
                    },
                    complete: function() {
                        console.log('Done');
                    }
                });

                Alerts.add('Удалено', 'success');
            }
        }
    }
</script>