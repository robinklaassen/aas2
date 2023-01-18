<template>
  <div class="c-roles-explanation">
    <input v-model="filter" class="form-control" placeholder="Zoek tekst, zoals: Deelnemer"/>
    <table class="c-roles-explanation__table">
      <thead>
      <tr>
        <th>
          <!-- Capability description -->
        </th>

        <th v-for="role of roles" :key="role.id">{{ role.title }}</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="capability of filteredCapabilities">
        <th>{{ capability.description }}</th>
        <td
            :class="{
              'c-roles-explanation__table__cell': true,
              '--active': capabilitiesPerRole[role.id].includes(capability.id)
            }"
            v-for="role of roles"
            :key="role.id"
        ></td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script lang="ts">
type Capability = {
  description: string;
}

export default {
  props: {
    roles: Array,
    capabilities: Array as () => Capability[],
    capabilitiesPerRole: Object,
  },
  data() {
    return {
      filter: "" as string,
    }
  },
  computed: {
    filteredCapabilities(): Capability[]  {
      const filterValue = this.filter.toLowerCase();
      if (!filterValue) {
        return this.capabilities;
      }

      return this.capabilities.filter((c) => c.description.toLowerCase().includes(filterValue));
    }
  }
};
</script>
