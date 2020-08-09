<style lang="sass" scoped>
    .row
        margin-top: 0.5em
</style>
<script lang="ts">
import Vue from 'vue'
import moment from 'moment';
import { Errors } from '../../form/errors';

export default Vue.extend({
    props: {
        file: File,
        errors: Errors,
        index: Number,
    },
    data() {
        return {
            amount: '0.00',
            description: '',
            date: moment().format("YYYY-MM-DD"),
            gift: false,
        };
    },
    methods: {
        removeRow() {
            this.$emit('declaration-remove');
        }
    }
})
</script>
<template>
<div class="row">
    <div class="col-md-2">
        {{ file.name }}
    </div>

    <div class="col-md-2">
        <div :class="{ 'form-group': true, 'has-error': this.errors.has('data.' + index + '.date') } ">
            <input
                class="form-control"
                v-model="date"
                type="date"
                @input="this.errors.clear('data.' + index + '.date')"
            />
            <span class="help-block">
                <errors :errors="this.errors.get('data.' + index + '.date') "></errors>
            </span>
        </div>

    </div>

    <div class="col-md-2">
        <div :class="{ 'form-group': true, 'has-error': this.errors.has('data.' + index + '.amount') } ">
            <div class="input-group">
                <span class="input-group-addon">&euro;</span>
                <input 
                    class="form-control"
                    v-model="amount"
                    type="number"
                    @input="this.errors.clear('data.' + index + '.amount')"
                />
            </div>
            <span class="help-block">
                <errors :errors="this.errors.get('data.' + index + '.amount') "></errors>
            </span>
        </div>
    </div>

    <div class="col-md-4">
        <div :class="{ 'form-group': true, 'has-error': this.errors.has('data.' + index + '.description') } ">
            <input 
                class="form-control"
                v-model="description"
                @input="this.errors.clear('data.' + index + '.description')"
            />
        </div>
        <span class="help-block">
            <errors :errors="this.errors.get('data.' + index + '.description') "></errors>
        </span>
    </div>

    <div class="col-md-1">
        <div :class="{ 'form-group': true, 'has-error': this.errors.has('data.' + index + '.gift') } ">
            <input
                type="checkbox"
                v-model="gift"
                @input="this.errors.clear('data.' + index + '.gift')"
            />
        </div>
        <span class="help-block">
            <errors :errors="this.errors.get('data.' + index + '.gift') "></errors>
        </span>
    </div>
    <div class="col-md-1">
        <a @click.prevent="removeRow">
            <i class="glyphicon glyphicon-remove"></i>
        </a>
    </div>
</div>
</template>