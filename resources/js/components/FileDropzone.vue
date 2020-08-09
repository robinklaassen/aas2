<template>
    <div 
        :class="{ 'file-drop': true, '-hover': isHovering }"
        @drop.prevent="fileDrop"
        @dragover.prevent
        @dragenter="dragEnter"
        @dragleave="dragLeave"
    >
        <label>
            <div v-if="!hasFiles">{{ labelText }}</div>
            <div v-if="hasFiles">
                {{ fileCount }} bestanden geselecteerd.
            </div>
            <span class="btn btn-primary">Selecteer</span>
            <input 
                ref="input"
                type="file"
                :multiple="multiple"
                @change="filesChanged"
            />
        </label>
        <div class="selected-files" v-if="hasFiles && listing">
            <ul class="list-group">
                <li class="list-group-item" v-for="(file, index) in files" :key="index">
                    <span>{{ file.name }}</span>
                    <a @click="removeItem(index)">
                        <i class="glyphicon glyphicon-remove"></i>
                    </a>
                </li>
            </ul>
        </div>  
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
        files: {
            type: Array,
            default: [] as File[],
        },
    },
    data() {
        return {
            isHovering: false,
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
            const input = this.$refs.input as HTMLInputElement
            if (input.files) {
                this.files.push(...this.filterFileList(input.files));
            }
        },
        dragEnter(evt: DragEvent) {
            this.isHovering = true;
        },
        fileDrop(evt: DragEvent) {
            if (!evt.dataTransfer) {
                return;
            }
            
            this.files.push(...this.filterFileList(evt.dataTransfer.files))
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
        removeItem(idx: number) {
            this.files.splice(idx, 1);
        }
    },
});
</script>