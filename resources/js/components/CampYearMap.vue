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

    <div class="map-container">
      <l-map :zoom="zoom" :center="center">
        <l-tile-layer :url="mapUrl" :attribution="attribution"></l-tile-layer>
        <l-circle-marker
          v-for="x in filteredCampData"
          :key="x.id"
          :lat-lng="x.latlng"
          :radius="x.size * 2" 
          color="#95184d"
          fill-color="#95184d"
          ><l-tooltip>
            {{ x.title }}<br/>
            {{ x.numParticipants }} deelnemers, {{ x.numMembers }} leiding
          </l-tooltip>
        </l-circle-marker>
      </l-map>
    </div>
  </div>
</template>

<style scoped>
.map-container {
  height: 900px;
  margin-top: 30px;
}
</style>

<script lang="ts">
import { LMap, LTileLayer, LCircleMarker, LTooltip } from "@vue-leaflet/vue-leaflet";
import VueSlider from "vue-slider-component";
import "vue-slider-component/theme/default.css";
import _ from "lodash";

import { STAMEN_TERRAIN_BG_MAP_URL, STAMEN_TERRAIN_BG_MAP_ATTRIBUTION } from "../mapConstants";

type CampMapData = {
  id: number;
  title: string;
  verenigingsjaar: string;
  latlng: Array<number>;
  numMembers: number;
  numParticipants: number;
  size: number;
};

const NETHERLANDS_CENTER_LAT_LNG = [52.1, 5.4];
const NETHERLANDS_ZOOM_LEVEL = 8;

export default {
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
      mapUrl: STAMEN_TERRAIN_BG_MAP_URL,
      attribution: STAMEN_TERRAIN_BG_MAP_ATTRIBUTION,
      zoom: NETHERLANDS_ZOOM_LEVEL,
      center: NETHERLANDS_CENTER_LAT_LNG,
      selectedYear: this.campData[0]?.verenigingsjaar,
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
};
</script>