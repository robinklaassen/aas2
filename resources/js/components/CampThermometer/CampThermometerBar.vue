<script lang="ts">
import Vue from "vue";

export default Vue.extend({
  props: {
    label: String,
    numberFull: Number,
    numberPartial: Number,
    targetNumber: Number,
  },
  computed: {
      percentageFull(): number {
          return Math.min(this.numberFull / this.targetNumber, 1) * 100;
      },
      percentagePartial(): number {
          return Math.min(this.numberPartial / this.targetNumber, 1 - this.percentageFull) * 100
      },
      styleBarFull(): string {
          return `width: ${this.percentageFull}%`
      },
      styleBarPartial(): string {
          return `width: ${this.percentagePartial}%`
      }
  }
});
</script>

<style scoped>
.progress {
  height: 30px;
  width: 100%;
}

.progress .progress-bar {
  font-size: 140%;
  line-height: normal;
}

.progress-label-left {
  width: 20px;
  margin-right: 10px;
  font-size: 21px;
  font-weight: 200;
}

.progress-label-right {
  width: 4em;
  margin-left: 10px;
  font-size: 21px;
  font-weight: 200;
}
</style>

<template>
  <div style="display: flex">
    <div class="progress-label-left">{{ label }}</div>
    <div class="progress">
      <div
        v-if="numberFull > 0"
        class="progress-bar progress-bar-success"
        role="progressbar"
        :style="styleBarFull"
      >
        {{ numberFull }}
      </div>
      <div
        v-if="numberPartial > 0"
        class="progress-bar progress-bar-warning"
        role="progressbar"
        :style="styleBarPartial"
      >
        {{ numberPartial }}
      </div>
    </div>
    <div class="progress-label-right">
      {{ numberFull + numberPartial }} / {{ targetNumber }}
    </div>
  </div>
</template>
