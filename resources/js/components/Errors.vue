<template>
    <ul class="form-errors">
        <li class="text-danger" v-for="error in errors" :key="error">{{ error }}</li>
    </ul>
</template>
<style lang="sass" scoped>
 
</style>
<script lang="ts">
export default {
    props: {
        errors: Array,
    },
};
</script>