<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">
        <top-panel
          :title="`${warehouse.name} - převodka`"
          title-class="text-warning"
          :back-url="`/warehouses/${$route.params.warehouse_id}`"></top-panel>

        <form @submit.prevent="onSubmit()" id="movements_form">

          <div class="card mt-3">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <label for="issue_warehouse_id">Sklad - Výdej</label>
                  <select @change="fetchPriceLevels()" v-model="form.issue_warehouse_id" class="form-control" name="issue_warehouse_id" id="issue_warehouse_id">
                      <option
                        :selected="parseInt(warehouse.id) === parseInt($route.params.warehouse_id)"
                        v-for="warehouse of warehouses"
                        :value="warehouse.id">
                        {{ warehouse.name }}
                      </option>
                  </select>

                  <div v-if="hasError('issue_warehouse_id')" class="invalid-feedback d-block">{{ getError('issue_warehouse_id') }}</div>
                </div>
                <div class="col">
                  <label for="receipt_warehouse_id">Sklad - příjem</label>
                  <select v-model="form.receipt_warehouse_id" class="form-control" name="receipt_warehouse_id" id="receipt_warehouse_id">
                    <option
                      v-for="warehouse of warehouses"
                      :value="warehouse.id">
                      {{ warehouse.name }}
                    </option>
                  </select>

                  <div v-if="hasError('receipt_warehouse_id')" class="invalid-feedback d-block">{{ getError('receipt_warehouse_id') }}</div>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col">
                  <label for="amount">Množství (Skladem: {{ getMaxAmount() }} {{ getWarehouseProduct().unit }})</label>
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
                  <select v-model="form.price_level_id" class="form-control" name="price_level_id" id="price_level_id">
                    <option v-for="priceLevel of priceLevels" :key="priceLevel.id" :value="priceLevel.id">{{ priceLevel.price }}</option>
                  </select>

                  <div v-if="hasError('price_level_id')" class="invalid-feedback d-block">{{ getError('price_level_id') }}</div>
                </div>
                <div class="col">
                  <label for="product">Produkt</label>
                  <input class="form-control" readonly type="text" id="product" :value="getWarehouseProduct().name">
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
import TopPanel from "~/components/TopPanel";
import NumericPad from "~/components/NumericPad";

export default {
  name: "WarehouseProductIssue",
  components: {TopPanel, NumericPad},
  async asyncData({params, store}) {
      let warehouses = await store.dispatch('warehouses/fetchAll');
      let warehouse = await store.dispatch('warehouses/fetch', params.warehouse_id);

      return {
          warehouses: warehouses.data,
          warehouse
      }
  },
  mounted() {
    this.form.product_id = this.getWarehouseProduct().id;
    this.form.issue_warehouse_id = this.warehouses.find(warehouse => warehouse.type === 'warehouse').id;

    let receipt_warehouse = this.warehouses.find(warehouse => warehouse.id === this.$auth.user.warehouse_id);
    if (!receipt_warehouse || this.form.issue_warehouse_id === receipt_warehouse.id) {
      receipt_warehouse = this.warehouses.find(warehouse => warehouse.type === 'temporary_warehouse');
    }

    let receiptWarehouseId = window.localStorage.getItem('receiptWarehouseId');

    let receipt_warehouse_id = receipt_warehouse.id;
    if (receiptWarehouseId) {
      receipt_warehouse_id = receiptWarehouseId;
    }

    if (this.warehouse.type === 'trash_warehouse') {
      receipt_warehouse_id = this.warehouse.id;
    }

    this.form.receipt_warehouse_id = receipt_warehouse_id;

    this.fetchPriceLevels();
  },
  data() {
    return {
      warehouses: [],
      warehouse: null,
      priceLevels: [],
      loading: false,
      form: {
        amount: 0,
        price_level_id: null,
        issue_warehouse_id: null,
        receipt_warehouse_id: null,
        product_id: null,
        user_id: this.$auth.user.id,
      }
    }
  },
  methods: {
    updateValue(val) {
      this.form[val.type] = val.value;
    },
    async onSubmit() {
      this.loading = true;
      await this.$store.dispatch('movements/transmission', this.form)
      .then(r => {
        this.$router.push(`/warehouses/${this.$route.params.warehouse_id}`)
      })
      .catch(r => {
        console.log('transmission:', r);
        this.loading = false;
      });
    },
    async fetchPriceLevels() {
      let warehouse_id = this.form.issue_warehouse_id;

      let priceLevels = await this.$store.dispatch('priceLevels/fetchAll', {
        warehouse_id,
        product_id: this.$route.params.product_id
      });

      this.priceLevels = priceLevels.data;

      // Pre select
      let priceLevel = this.priceLevels[0];
      if (priceLevel) {
        this.form.price_level_id = priceLevel.id
      }
    },
    getMaxAmount() {
      let price_level = this.priceLevels.find(pl => pl.id === this.form.price_level_id);

      return (price_level) ? price_level.amount : 0;
    },
    // getPriceLevels() {
    //   // Fetch proper price levels (based on issue warehouse product)
    //
    //   let priceLevels = this.getWarehouseProduct().priceLevels
    //
    //   return priceLevels.filter(
    //     priceLevel => parseInt(priceLevel.warehouse_id) === parseInt(this.$route.params.warehouse_id));
    // },
    getWarehouseProduct() {
      let product_id = this.$route.params.product_id;

      return this.warehouse.products.find(product => product.id === parseInt(product_id));
    },
    setCurrentInput(type) {
      this.$refs.num_pad.setCurrentInput(type);
    },
  },
  watch: {
    receiptWarehouseId(newValue, oldValue) {
      window.localStorage.setItem('receiptWarehouseId', newValue);
    }
  },
  computed: {
    receiptWarehouseId() {
      return this.form.receipt_warehouse_id;
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
