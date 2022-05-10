<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">
        <top-panel :title="'Sklady'"></top-panel>

        <div class="row">
            <div v-for="warehouse of warehouses" :key="warehouse.id"
                 v-if="warehouse.type !== 'trash'"
                 class="col-6 col-md-4 col-lg-3 mt-3"
            >
              <nuxt-link :to="`/warehouses/${warehouse.id}`" class="fw-bold text-decoration-none text-black">
                <div class="card" style="min-height: 100px;">
                  <div class="card-body" style="display:flex; align-items: center; justify-content: center;">
                    <div class="text-center font-weight-bold">
                      {{ warehouse.name }}
                    </div>
                  </div>
                </div>
              </nuxt-link>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import {mapGetters} from 'vuex';
import TopPanel from "~/components/TopPanel";

export default {
  name: "WarehousesIndex",
  components: {TopPanel},
  middleware: ['admin'],
  async fetch() {
      await this.$store.dispatch('warehouses/fetchAll');
  },
  computed: {
    ...mapGetters({
      warehouses: "warehouses/getAll",
    })
  },
}
</script>

<style scoped>

</style>
