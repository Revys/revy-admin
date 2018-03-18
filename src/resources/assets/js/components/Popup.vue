<template>
    <transition :name="transition" :duration="duration">
        <div class="popup-mask" v-show="show" v-prevent-parent-scroll>
            <div class="popup-wrapper" @click="$emit('close')">
                <div class="popup-container" @click.stop>
                    <div class="popup-content">
                        <slot></slot>
                    </div>

                    <div class="popup-close" @click="$emit('close')">×</div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        props: {
            transition: {
                default: 'popup'
            },
            duration: {
                default: null
            }
        },

        data() {
            return {
                show: false
            }
        },

        mounted(){
            this.$on('show', function() { 
                this.show = true;
            });
            this.$on('close', function() { 
                this.show = false;
            });
        },

        mixins: {
            methods: {
                showPopup(name) {
                    if (vm.$refs[name] == undefined) 
                        return console.error("[Vue/Popup]: Popup '" + name + "' not found");

                    vm.$refs[name].$emit('show');

                    // document.getElementByTagName('body').style.overflow = 'hidden';
                    window.addEventListener("scroll", function(){
                        return false;
                    });
                }
            }
        }
    }
        
</script>