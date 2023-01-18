<style lang="sass" scoped>
.row
    margin-top: 0.5em
</style>
<script lang="ts">
import {Errors} from '../../form/errors';
import {DeclarationRow} from './data/DeclarationRow';

export default {
    props: {
        row: DeclarationRow,
        errors: Errors,
        index: Number,
    },
    data() {
        return {};
    },
    methods: {}
};
</script>
<template>
    <div class="row">
        <div class="col-md-2">

            <div class="form-group">
                <label class="control-label hidden-md hidden-lg">Bestand</label>
                <p class="form-control-static">{{ row.fileName }}</p>
            </div>
        </div>

        <div class="col-md-2">
            <div :class="{ 'form-group': true, 'has-error': errors.has('data.' + index + '.date') } ">
                <label for="date" class="hidden-md hidden-lg">Datum</label>
                <input
                    class="form-control"
                    v-model="row.date"
                    type="date"
                    id="date"
                    @input="errors.clear('data.' + index + '.date')"
                />
                <span class="help-block">
                <errors :errors="errors.get('data.' + index + '.date') "></errors>
            </span>
            </div>

        </div>

        <div class="col-md-2">
            <div :class="{ 'form-group': true, 'has-error': errors.has('data.' + index + '.amount') } ">
                <label for="amount" class="hidden-md hidden-lg">Bedrag</label>
                <div class="input-group">
                    <span class="input-group-addon">&euro;</span>
                    <input
                        class="form-control"
                        v-model="row.amount"
                        type="number"
                        id="amount"
                        @input="errors.clear('data.' + index + '.amount')"
                    />
                </div>
                <span class="help-block">
                <errors :errors="errors.get('data.' + index + '.amount') "></errors>
            </span>
            </div>
        </div>

        <div class="col-md-3">
            <div :class="{ 'form-group': true, 'has-error': errors.has('data.' + index + '.description') } ">
                <label for="description" class="hidden-md hidden-lg">Omschrijving</label>

                <input
                    class="form-control"
                    v-model="row.description"
                    id="description"
                    @input="errors.clear('data.' + index + '.description')"
                />
            </div>
            <span class="help-block">
            <errors :errors="errors.get('data.' + index + '.description') "></errors>
        </span>
        </div>

        <div class="col-md-2">
            <div :class="{ 'form-group': true, 'has-error': errors.has('data.' + index + '.declaration_type') } ">
                <label for="description" class="hidden-md hidden-lg">Gift</label>

                <select
                    class="form-control"
                    id="declaration_type"
                    v-model="row.type"
                    @input="errors.clear('data.' + index + '.declaration_type')"
                >
                    <option value="pay">Uitbetalen</option>
                    <option value="gift">Gift aan Anderwijs</option>
                    <!-- From a member perspective you gift to the biomeat,
                         but from the system's perspective you pay to the biomeat -->
                    <option value="pay-biomeat">Gift aan het biovleespotje</option>
                </select>
            </div>
            <span class="help-block">
            <errors :errors="errors.get('data.' + index + '.declaration_type') "></errors>
        </span>
        </div>

        <div class="col-md-1">

            <div class="form-group">
                <label class="control-label hidden-md hidden-lg">Functies</label>
                <p class="form-control-static">
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
                </p>
            </div>
        </div>
        <hr class="hidden-md hidden-lg">
    </div>
</template>
