<script lang="ts">
import Vue from 'vue'
export default Vue.extend({
    data() {
        return {
            test:'value'
        };
    }
})
</script>
<template>
<div>
    <input v-model="test"/>
    <p>{{test}}</p>
</div>
</template>