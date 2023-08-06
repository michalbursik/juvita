<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">
        <top-panel :title="'Nová kontrola'" backUrl="/warehouses/checks">
        </top-panel>

        <div class="card mt-3">
          <div class="card-body form-group">
            <div class="row">
              <div class="col">
                <label for="warehouse_uuid">Sklad</label>
                <select v-model="warehouse_uuid" class="form-control" name="warehouse_uuid" id="warehouse_uuid">
                  <option v-for="warehouse of getWarehouses" :key="warehouse.uuid" :value="warehouse.uuid">
                    {{ warehouse.name }}
                  </option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <form v-if="warehouse_uuid" @submit.prevent="onSubmit()" class="mt-3">
          <div class="card">
            <div class="card-body">
              <div class="form-group">
                <table class="table">
                  <thead>
                  <tr>
                    <th scope="col">Název</th>
                    <th scope="col">Cena</th>
                    <th scope="col">Celkový počet</th>
                    <th scope="col">Aktuální počet</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr class="align-middle" v-for="price of productPrices">
                    <td>{{ price.product.name }}</td>
                    <td>{{ price.price }} Kč</td>
                    <td>{{ price.amount }} {{ price.product.unit }}</td>
                    <td>
                        <input @input="syncPrices($event, price)"
                               class="form-control"
                               type="number"
                               id="amount"
                               name="amount"
                               step=".01"
                        >
                    </td>
                  </tr>
                  <tr v-if="getDiscount()">
                    <td colspan="3">Sleva</td>
                    <td>{{ getDiscount() }} Kč</td>
                  </tr>
                  </tbody>
                </table>

              </div>
            </div>
          </div>

          <button :disabled="loading.check" type="submit" class="btn btn-primary mt-2">
            <span v-if="loading.check" class="spinner-border spinner-border-sm text-light me-1" role="status"></span>
            Uložit
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import {mapGetters} from "vuex";
import TopPanel from "~/components/TopPanel";

export default {
  name: "WarehouseChecksIndex",
  components: {TopPanel},
  middleware: ['admin'],
  async asyncData({params, store}) {
    let warehouses = await store.dispatch('warehouses/fetchAll');
    let discounts = await store.dispatch('discounts/fetchAll', {
      perPage: 1000
    });

    return {
      warehouses: warehouses.data,
      discounts: discounts.data,
    }
  },
  mounted() {
    this.warehouse_uuid = this.warehouses[0].uuid;
  },
  data() {
    return {
      loading: {
        products: false,
        check: false,
      },
      warehouse_uuid: null,
      productPrices: [],
      chosenProducts: [],
      warehouses: [],
      discounts: [],
      new_product: {
        id: null,
        price_level_id: null,
        amount: null,
      }
    }
  },
  methods: {
    getDiscount() {
      let discounts = this.discounts.filter(discount => discount.warehouse.uuid === this.warehouse_uuid);

      return discounts.reduce((carry, discount) => carry - Number.parseFloat(discount.amount), 0.00);
    },
    async fetchProductPrices() {
      const response = await this.$store.dispatch('checks/fetchAllProductPrices', {
        warehouse_uuid: this.warehouse_uuid
      });

      this.productPrices = response.data;
    },
    syncPrices($el, price) {
      let amount = parseFloat($el.target.value);

      if (Number.isNaN(amount)) {
        return;
      }

      let new_product = {
        warehouse_product_uuid: price.warehouse_product_uuid,
        price_uuid: price.uuid,
        amount: amount,
      }

      let index = this.chosenProducts.findIndex(chosenProduct => {
        return chosenProduct.warehouse_product_uuid === price.warehouse_product_uuid &&
               chosenProduct.price_uuid === price.uuid
      });

      if (index >= 0) {
        if (amount >= 0) {
          this.chosenProducts.splice(index, 1, new_product);
        } else {
          this.chosenProducts.splice(index, 1);
        }
      } else {
        if (amount >= 0) {
          this.chosenProducts.push(new_product);
        }
      }

      console.log(this.chosenProducts);
    },
    async onSubmit() {
      let data = {
        warehouse_uuid: this.warehouse_uuid,
        products: this.chosenProducts
      }

      this.loading.check = true;
      await this.$store.dispatch('checks/store', data)
        .then(r => {
          localStorage.removeItem('check.create.warehouse_uuid');
          localStorage.removeItem('check.create.products.' + this.warehouse_uuid);

          this.$router.push('/warehouses/checks')
        })
        .catch(r => {
          this.loading.check = false;
        });
    },
  },
  watch: {
    warehouse_uuid(newValue, oldValue) {
      this.fetchProductPrices();
    }
  },
  computed: {
    ...mapGetters({
      getWarehouses: 'warehouses/getAll'
    }),
  },
}
</script>

<style scoped>

</style>
