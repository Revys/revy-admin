<template>
    <div class="images__image image">
        <img src="" :src="path" />
        <input type="hidden" disabled="true" name="_positions[]" :value="data.filename"/>
        <div class="image__delete" @click="$emit('remove', index)"></div>
    </div>
</template>

<script>
    export default {
        props: [
            'index',
            'data'
        ],
        computed: {
            path: function () {
                return '/storage/' + this.data.path;
            }
        }
    }
</script>