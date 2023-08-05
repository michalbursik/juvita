<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">
        <top-panel
          :title="`${currentWarehouse.name} - převodka`"
          title-class="text-warning"
          :back-url="`/warehouses/${currentWarehouse.uuid}`"></top-panel>

        <form @submit.prevent="onSubmit()" id="movements_form">

          <div class="card mt-3">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <label for="source_warehouse_uuid">Sklad - Výdej</label>
                  <select v-model="form.source_warehouse_uuid" class="form-control"
                          name="source_warehouse_uuid" id="source_warehouse_uuid">
                    <option
                      v-for="warehouse of warehouses"
                      :value="warehouse.uuid">
                      {{ warehouse.name }}
                    </option>
                  </select>

                  <div v-if="hasError('source_warehouse_uuid')" class="invalid-feedback d-block">
                    {{ getError('source_warehouse_uuid') }}
                  </div>
                </div>
                <div class="col">
                  <label for="target_warehouse_uuid">Sklad - příjem</label>
                  <select v-model="form.target_warehouse_uuid" class="form-control" name="target_warehouse_uuid"
                          id="target_warehouse_uuid">
                    <option
                      v-for="warehouse of warehouses"
                      :key="warehouse.uuid"
                      :value="warehouse.uuid">
                      {{ warehouse.name }}
                    </option>
                  </select>

                  <div v-if="hasError('target_warehouse_uuid')" class="invalid-feedback d-block">
                    {{ getError('target_warehouse_uuid') }}
                  </div>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col">
                  <label for="amount">Množství (Skladem: {{ getMaxAmount() }} {{ warehouse_product.unit }})</label>
                  <input @click="setCurrentInput('amount')"
                         class="form-control"
                         type="text"
                         id="amount"
                         name="amount"
                         v-model="form.amount"
                         readonly
                  >

                  <div v-if="hasError('amount')" v-html="getError('amount')" class="invalid-feedback d-block"></div>
                </div>
                <div class="col">
                  <label for="price_uuid">Cena</label>
                  <select v-model="form.price_uuid" class="form-control" name="price_uuid" id="price_uuid">
                    <option v-for="price of warehouse_product.prices" :key="price.uuid" :value="price.uuid">
                      {{ price.price }}
                    </option>
                  </select>

                  <div v-if="hasError('price_uuid')" class="invalid-feedback d-block">{{
                      getError('price_uuid')
                    }}
                  </div>
                </div>
                <div class="col">
                  <label for="product">Produkt</label>
                  <input class="form-control" readonly type="text" id="product" :value="warehouse_product.name">
                </div>
              </div>

              <numeric-pad :loading="loading" @change="updateValue" @submit="onSubmit()" ref="num_pad"></numeric-pad>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import {mapGetters} from "vuex";
import TopPanel from "~/components/TopPanel.vue";
import NumericPad from "~/components/NumericPad.vue";

export default {
  name: "WarehouseProductMove",
  components: {TopPanel, NumericPad},
  async asyncData({params, store}) {
    const warehouses = await store.dispatch('warehouses/fetchAll');
    const warehouse_product = await store.dispatch('warehouse_products/fetch', params.warehouse_product_uuid)
    const warehouse = await store.dispatch('warehouses/fetch', warehouse_product.warehouse_uuid);

    return {
      warehouses: warehouses.data,
      warehouse_product,
      currentWarehouse: warehouse
    }
  },
  mounted() {
    this.form.product_uuid = this.warehouse_product.product_uuid;
    this.form.price_uuid = this.warehouse_product.prices[0].uuid

    // Default source warehouse is main warehouse
    this.form.source_warehouse_uuid = this.warehouses.find(warehouse => warehouse.type === 'warehouse').uuid;

    // Default target warehouse is either user warehouse or first temporary warehouse
    // When we have remembered warehouse, it overrules it.
    let target_warehouse = this.warehouses.find(warehouse => warehouse.uuid === this.$auth.user.warehouse_uuid);
    if (!target_warehouse || this.form.source_warehouse_uuid === target_warehouse.uuid) {
      target_warehouse = this.warehouses.find(warehouse => warehouse.type === 'temporary_warehouse');
    }

    let rememberedTargetWarehouseUuid = window.localStorage.getItem('targetWarehouseUuid');

    let target_warehouse_uuid = target_warehouse.uuid;
    if (rememberedTargetWarehouseUuid) {
      target_warehouse_uuid = rememberedTargetWarehouseUuid;
    }

    // WHY ?
    // if (this.warehouse.type === 'trash_warehouse') {
    //   target_warehouse_uuid = this.warehouse.uuid;
    // }

    this.form.target_warehouse_uuid = target_warehouse_uuid;
  },
  data() {
    return {
      warehouses: [],
      currentWarehouse: null,
      warehouse_product: null,
      loading: false,
      form: {
        source_warehouse_uuid: null,
        target_warehouse_uuid: null,
        product_uuid: null,
        price_uuid: null,
        amount: 0,
      }
    }
  },
  methods: {
    updateValue(val) {
      this.form[val.type] = val.value;
    },
    async onSubmit() {
      this.loading = true;
      await this.$store.dispatch('warehouse_products/move', this.form)
        .then(r => {
          this.$router.push(`/warehouses/${this.currentWarehouse.uuid}`)
        })
        .catch(r => {
          console.log(r);
          this.loading = false;
        });
    },
    getMaxAmount() {
      let price = this.warehouse_product.prices.find(p => p.uuid === this.form.price_uuid);

      return (price) ? price.amount : 0;
    },
    setCurrentInput(type) {
      this.$refs.num_pad.setCurrentInput(type);
    },
  },
  watch: {
    targetWarehouseUuid(newValue, oldValue) {
      window.localStorage.setItem('targetWarehouseUuid', newValue);
    }
  },
  computed: {
    targetWarehouseUuid() {
      return this.form.target_warehouse_uuid;
    },
    ...mapGetters({
      getError: "validation/getFirst",
      hasError: "validation/exists",
    })
  },
}
</script>

<style scoped>

</style>
