<template>
    <div 
        :class="{ 'file-drop': true, '-hover': isHovering }"
        @drop.prevent="fileDrop"
        @dragover.prevent
        @dragenter="dragEnter"
        @dragleave="dragLeave"
    >
            <label>
                <slot name="label"></slot>
                <span class="btn btn-primary">Selecteer</span>
                <input 
                    ref="input"
                    type="file"
                    :multiple="multiple"
                    @change="filesChanged"
                />
            </label>
            <slot name="more"></slot>     
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
        listing: {
            type: Boolean,
            default: true,
        },
        multiple: {
            type: Boolean,
            default: true,
        },
        mimetypes: Array,
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
    },
    methods: {
        filesChanged() {
            const input = this.$refs.input as HTMLInputElement
            if (input.files) {
                this.addFiles(this.filterFileList(input.files));
            }
            input.value = "";
        },
        dragEnter(evt: DragEvent) {
            this.isHovering = true;
        },
        fileDrop(evt: DragEvent) {
            if (!evt.dataTransfer) {
                return;
            }
            
            this.addFiles(this.filterFileList(evt.dataTransfer.files));
            this.isHovering = false;
        },
        dragLeave() {
            this.isHovering = false;
        },
        filterFileList(files: FileList): File[] {
            const mimeTypes = this.mimetypes as string[];
            return Array.from(files).filter((f) => {
                return mimeTypes.includes(f.type);
            })
        },
        addFiles(files: File[]) {
            this.files.push(...files);
            this.$emit('files-uploaded', files);
        }
    },
});
</script>