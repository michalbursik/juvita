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
              <nuxt-link :to="`/warehouses/${warehouse.uuid}`" class="fw-bold text-decoration-none text-black">
                <div @contextmenu.prevent="openContextMenu(warehouse.id, $event)" class="card" style="min-height: 100px;">
                  <div class="card-body" style="display:flex; align-items: center; justify-content: center;">
                    <div class="text-center font-weight-bold">
                      {{ warehouse.name }}
                    </div>
                  </div>
                </div>
              </nuxt-link>

              <vue-context :ref="`contextmenu-${warehouse.id}`" tag="div"
                           class="p-2"
                           style="max-width: 200px;"
              >
                <div class="d-flex align-items-center justify-content-center">
                  <button @click="editWarehouse(warehouse.uuid)" type="button" class="btn btn-outline-primary me-2">Upravit</button>
                  <button @click="deleteWarehouse(warehouse.uuid)" type="button" class="btn btn-outline-danger">Smazat</button>
                </div>
              </vue-context>
            </div>

            <div class="col-6 col-md-4 col-lg-3 mt-3"
            >
              <nuxt-link :to="`/warehouses/create`" class="fw-bold text-decoration-none text-black">
                <div class="card" style="min-height: 100px;">
                  <div class="card-body" style="display:flex; align-items: center; justify-content: center;">
                    <div class="text-center font-weight-bold" style="font-size: 2rem">
                      +
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
import VueContext from 'vue-context';

export default {
  name: "WarehousesIndex",
  components: {TopPanel, VueContext},
  middleware: ['admin'],
  async fetch() {
      await this.$store.dispatch('warehouses/fetchAll');
  },
  computed: {
    ...mapGetters({
      warehouses: "warehouses/getAll",
    })
  },
  methods: {
    editWarehouse(warehouse_id) {
      this.$router.push(`warehouses/${warehouse_id}/edit`)
    },
    async deleteWarehouse(warehouse_id) {
      await this.$store.dispatch('warehouses/delete', warehouse_id);
    },
    openContextMenu(warehouse_id, $event) {
      let refName = `contextmenu-${warehouse_id}`;

      let ref = this.$refs[refName]

      if (Array.isArray(ref)) {
        ref = ref[0];
      }

      ref.open($event);
    }
  },
}
</script>

<style scoped>

</style>
