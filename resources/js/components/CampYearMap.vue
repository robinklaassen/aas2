<template>
  <div id="camp-year-map-root">
    <div class="form-group">
      <label for="yearSlider">Verenigingsjaar</label>
      <vue-slider
        id="yearSlider"
        v-model="selectedYear"
        :data="sliderData"
        :marks="true"
      ></vue-slider>
    </div>

    <l-map class="main-map" :zoom="zoom" :center="center">
      <l-tile-layer :url="mapUrl" :attribution="attribution"></l-tile-layer>
      <l-circle-marker
        v-for="x in filteredCampData"
        :key="x.id"
        :lat-lng="x.latlng"
        :radius="x.size * 2"
        color="red"
        ><l-tooltip>
          {{ x.titel }}
        </l-tooltip>
      </l-circle-marker>
    </l-map>
  </div>
</template>

<style scoped>
.main-map {
  height: 900px;
  margin-top: 30px;
}
</style>

<script lang="ts">
import Vue from "vue";
import { LMap, LTileLayer, LCircleMarker, LTooltip } from "vue2-leaflet";
import VueSlider from "vue-slider-component";
import "vue-slider-component/theme/default.css";
import _ from "lodash";

type CampMapData = {
  id: number;
  titel: string;
  verenigingsjaar: string;
  latlng: Array<number>;
  size: number;
};

export default Vue.extend({
  props: {
    campData: Array as () => CampMapData[],
  },
  components: {
    LMap,
    LTileLayer,
    LCircleMarker,
    LTooltip,
    VueSlider,
  },
  data() {
    return {
      mapUrl:
        "https://stamen-tiles-{s}.a.ssl.fastly.net/terrain-background/{z}/{x}/{y}{r}.png",
      attribution:
        'Map tiles by <a href="http://stamen.com">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      zoom: 8,
      center: [52.1, 5.4],
      selectedYear: this.campData[0].verenigingsjaar,
    };
  },
  computed: {
    filteredCampData(): CampMapData[] {
      return this.campData.filter(
        (x) => x.verenigingsjaar == this.selectedYear
      );
    },
    sliderData(): string[] {
      return _.uniq(this.campData.map((x) => x.verenigingsjaar)).sort();
    },
  },
});
</script>