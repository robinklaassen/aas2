<template>
    <div 
        :class="{ 'file-drop': true, '-hover': isHovering }"
        
    >
        <label>
            <div :if="!hasFiles"></div>
            <input 
                ref="input"
                type="file"
                :name="name"
                :multiple="multiple"
                @change="filesChanged"
            />
        </label>
        <span>{{  }}</span>
        
    </div>
</template>
<style lang="sass" scoped>
    .file-drop
        padding: 2em
        border: 1px solid #000
        border-radius: 0.2em
        text-align: center

        &.-hover
            border-color: #2780E3



    input[type='file']
        height: 0.1px
        width: 0.1px
        overflow: hidden
        opacity: 0
        z-index: -1
        position: absolute

</style>
<script lang="ts">
import Vue from 'vue'
export default Vue.extend({
    props: {
        name: String,
        multiple: Boolean,
    },
    data() {
        return {
            isHovering: false,
            files: [] as File[],
        };
    },
    computed: {
        hasFiles(): boolean {
            return this.files.length > 0
        },
        fileCount(): number {
            return this.files.length;
        },
        labelText(): string {
            return `Selecteer één ${this.multiple ? 'of meerdere bestanden' : 'bestand'}`;
        }
    },
    methods: {
        filesChanged() {
            console.log(this.$refs.input)
        },
    },
})
</script>