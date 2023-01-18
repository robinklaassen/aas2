<template>
    <div :class="['alert', type]"
        role="alert"
    >
        <slot></slot>
    </div>
</template>
<style lang="sass" scoped>

</style>
<script lang="ts">
export default {
    props: {
        type: String,
        showTime: Number
    },
    mounted() {
        $(this.$el).delay(this.showTime).slideUp(300);
    }
};
</script>