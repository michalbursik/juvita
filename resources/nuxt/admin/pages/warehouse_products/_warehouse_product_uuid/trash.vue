<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">
        <top-panel
          :title="`${warehouse.name} - odpad`"
          title-class="text-danger"
          :back-url="`/warehouses/${warehouse.uuid}`"></top-panel>

        <form @submit.prevent="onSubmit()" id="movements_form">

          <div class="card mt-3">
            <div class="card-body">
              <div class="row">
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
                  <label for="price_level_id">Cena</label>
                  <select v-model="form.price_uuid" class="form-control" name="price_uuid" id="price_uuid">
                    <option v-for="price_uuid of warehouse_product.prices" :key="price_uuid.uuid"
                            :value="price_uuid.uuid">
                      {{ price_uuid.price }}
                    </option>
                  </select>

                  <div v-if="hasError('warehouse_product_price_uuid')" class="invalid-feedback d-block">{{
                      getError('warehouse_product_price_uuid')
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
  name: "WarehouseProductIssue",
  components: {TopPanel, NumericPad},
  async asyncData({params, store}) {
    let warehouse_product = await store.dispatch('warehouse_products/fetch', params.warehouse_product_uuid)
    let warehouse = await store.dispatch('warehouses/fetch', warehouse_product.warehouse_uuid);

    return {
      warehouse,
      warehouse_product
    }
  },
  mounted() {
    this.form.price_uuid = this.warehouse_product.prices[0].uuid
    this.form.product_uuid = this.warehouse_product.product_uuid
    this.form.warehouse_product_uuid = this.warehouse_product.uuid
  },
  data() {
    return {
      warehouse: null,
      warehouse_product: null,
      loading: false,
      form: {
        amount: 0,
        price_uuid: null,
        product_uuid: null,
        warehouse_product_uuid: null,
      }
    }
  },
  methods: {
    updateValue(val) {
      this.form[val.type] = val.value;
    },
    async onSubmit() {
      this.loading = true;
      await this.$store.dispatch('warehouse_products/trash', this.form)
        .then(r => {
          this.$router.push(`/warehouses/${this.warehouse.uuid}`)
        })
        .catch(r => {
          console.log(r);
          this.loading = false;
        });
    },
    getMaxAmount() {
      const price = this.warehouse_product.prices.find(p => p.uuid === this.form.price_uuid)

      return (price) ? price.amount : '??';
    },
    setCurrentInput(type) {
      this.$refs.num_pad.setCurrentInput(type);
    },
  },
  computed: {
    ...mapGetters({
      getError: "validation/getFirst",
      hasError: "validation/exists",
    })
  },
}
</script>

<style scoped>

</style>
