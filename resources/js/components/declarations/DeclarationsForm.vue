<template>
<div>
    <file-dropzone
        :files="files"
        :multiple="true"
        :mimetypes="['image/jpeg', 'image/png']"
        :listing="false"
    ></file-dropzone>

    <div class="declarations-form">
        <div class="row header">
            <div class="col-md-2">
                Bestand
            </div>

            <div class="col-md-2">
                Datum
            </div>

            <div class="col-md-2">
                Bedrag
            </div>

            <div class="col-md-4">
                Omschrijving
            </div>

            <div class="col-md-1">
                <i class="glyphicon glyphicon-gift"></i>
            </div>

        </div>

        <declaration-input-row 
            v-for="(file, index) in files"
            :key="index"
            :file="file"
            :index="index"
            :errors="errors"
            ref="datarows"
            v-on:declaration-remove="removeRow(index)"
        ></declaration-input-row>
    </div>

    <p class="declarations-error">{{errorMessage}}</p>

    <button class="btn btn-primary" @click.prevent="submit" :disabled="isLoading">
        Versturen
    </button>
</div>
</template>
<style lang="sass" scoped>
    .declarations-form 
        margin:
            top: 2em
            bottom: 2em
        .row.header
            border-bottom: 1px solid #000
            font-weight: 300

</style>
<script lang="ts">
import Vue from 'vue'
import Axios from 'axios';
import { Errors } from "../../form/errors"

export default Vue.extend({
    props: {
        target: String,
        redirectTarget: String,
        csrf: String,
    },
    data() {
        return {
            files: [] as File[],
            isLoading: false,
            errorMessage: "",
            errors: new Errors()
        };
    },
    methods: {
        removeRow(idx: number) {
            this.files.splice(idx, 1);
        },
        async submit() {
            this.isLoading = true;
            this.errorMessage = "";
            
            try {
                const res = await Axios.post(
                    this.target,
                    this.getFormData(),
                    {
                        headers: {
                            "X-CSRF-TOKEN": this.csrf
                        },
                        maxRedirects: 0,
                    }
                );
                
                window.location.href = this.redirectTarget;
            } catch(err) {
                if (
                    err.request.status >= 400 
                    && err.request.status < 500 
                    && err.response.data.errors
                ) {
                    this.errors.load(err.response.data.errors);
                } else if (err.request) {
                    this.errorMessage = err.response.data.message;
                } else {
                    this.errorMessage = err.message;
                }
            }
            this.isLoading = false;
        },
        getFormData(): FormData {

            const rows = this.$refs.datarows as any[];

            const formData = new FormData;
            for (let index = 0; index < rows.length; index++) {
                const row = rows[index];
                const prefix = `data[${index}]`;
                formData.append(`${prefix}[file]`, row.file);
                formData.append(`${prefix}[date]`, row.date);
                formData.append(`${prefix}[amount]`, row.amount);
                formData.append(`${prefix}[description]`, row.description);
                formData.append(`${prefix}[gift]`, row.gift ? "1" : "0");
            }

            return formData;
        }
    }
})
</script>
