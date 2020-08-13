<style lang="sass" scoped>
    .row
        margin-top: 0.5em
</style>
<script lang="ts">
import Vue from 'vue'
import moment from 'moment';
import { Errors } from '../../form/errors';
import { DeclarationRow } from './data/DeclarationRow';

export default Vue.extend({
    props: {
        row: DeclarationRow,
        errors: Errors,
        index: Number,
    },
    data() {
        return {};
    },
    methods: {
    }
})
</script>
<template>
<div class="row">
    <div class="col-md-2">
        {{ row.fileName }}
    </div>

    <div class="col-md-2">
        <div :class="{ 'form-group': true, 'has-error': errors.has('data.' + index + '.date') } ">
            <input
                class="form-control"
                v-model="row.date"
                type="date"
                @input="errors.clear('data.' + index + '.date')"
            />
            <span class="help-block">
                <errors :errors="errors.get('data.' + index + '.date') "></errors>
            </span>
        </div>

    </div>

    <div class="col-md-2">
        <div :class="{ 'form-group': true, 'has-error': errors.has('data.' + index + '.amount') } ">
            <div class="input-group">
                <span class="input-group-addon">&euro;</span>
                <input 
                    class="form-control"
                    v-model="row.amount"
                    type="number"
                    @input="errors.clear('data.' + index + '.amount')"
                />
            </div>
            <span class="help-block">
                <errors :errors="errors.get('data.' + index + '.amount') "></errors>
            </span>
        </div>
    </div>

    <div class="col-md-4">
        <div :class="{ 'form-group': true, 'has-error': errors.has('data.' + index + '.description') } ">
            <input 
                class="form-control"
                v-model="row.description"
                @input="this.errors.clear('data.' + index + '.description')"
            />
        </div>
        <span class="help-block">
            <errors :errors="errors.get('data.' + index + '.description') "></errors>
        </span>
    </div>

    <div class="col-md-1">
        <div :class="{ 'form-group': true, 'has-error': errors.has('data.' + index + '.gift') } ">
            <input
                type="checkbox"
                v-model="row.gift"
                @input="errors.clear('data.' + index + '.gift')"
            />
        </div>
        <span class="help-block">
            <errors :errors="errors.get('data.' + index + '.gift') "></errors>
        </span>
    </div>
    <div class="col-md-1">
        <a @click.prevent="$emit('declaration-copy', row)"
            title="Regel dupliceren"
        >
            <i class="glyphicon glyphicon-copy"></i>
        </a>
        <a @click.prevent="$emit('declaration-remove', row)" 
            title="Regel verwijderen"
        >
            <i class="glyphicon glyphicon-remove"></i>
        </a>
    </div>
</div>
</template>