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
                <label for="warehouse_id">Sklad</label>
                <select v-model="warehouse_id" class="form-control" name="warehouse_id" id="warehouse_id">
<!--                  <option selected value="">Vyberte sklad</option>-->
                  <option v-for="warehouse of getWarehouses" :key="warehouse.id" :value="warehouse.id">{{
                      warehouse.name
                    }}
                  </option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <form v-if="warehouse_id" @submit.prevent="onSubmit()" class="mt-3">
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
                  <tr class="align-middle" v-for="priceLevel of getPriceLevels">
                    <td>{{ priceLevel.product.name }}</td>
                    <td>{{ priceLevel.price }} Kč</td>
                    <td>{{ priceLevel.amount }} {{ priceLevel.product.unit }}</td>
                    <td>
                        <input @input="syncPriceLevel($event, priceLevel)"
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
    let warehouses = await store.dispatch('warehouses/fetchAll', {
      with: ['priceLevels.product']
    });

    let discounts = await store.dispatch('discounts/fetchAll', {
      perPage: 1000
    });

    return {
      warehouses: warehouses.data,
      discounts: discounts.data,
    }
  },
  mounted() {
    this.warehouse_id = this.warehouses[0].id;
  },
  data() {
    return {
      loading: {
        products: false,
        check: false,
      },
      warehouse_id: null,
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
      let discounts = this.discounts.filter(discount => discount.warehouse.id === this.warehouse_id);

      return discounts.reduce((carry, discount) => carry - Number.parseFloat(discount.amount), 0.00);
    },
    syncPriceLevel($el, priceLevel) {
      let amount = parseFloat($el.target.value);

      if (Number.isNaN(amount)) {
        return;
      }

      let new_product = {
        warehouse_id: priceLevel.warehouse_id,
        product_id: priceLevel.product_id,
        price_level_id: priceLevel.id,
        amount: amount,
      }

      let index = this.chosenProducts.findIndex(chosenProduct => {
        return parseInt(chosenProduct.warehouse_id) === parseInt(priceLevel.warehouse_id) &&
               parseInt(chosenProduct.product_id) === parseInt(priceLevel.product_id) &&
               parseInt(chosenProduct.price_level_id) === parseInt(priceLevel.id)
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
    },
    async onSubmit() {
      let data = {
        warehouse_id: this.warehouse_id,
        products: this.chosenProducts
      }

      this.loading.check = true;
      await this.$store.dispatch('checks/store', data)
        .then(r => {
          localStorage.removeItem('check.create.warehouse_id');
          localStorage.removeItem('check.create.products.' + this.warehouse_id);

          this.$router.push('/warehouses/checks')
        })
        .catch(r => {
          console.log(r);
          this.loading.check = false;
        });
    },
  },
  computed: {
    ...mapGetters({
      getWarehouses: 'warehouses/getAll'
    }),
    getPriceLevels() {
      let warehouse = this.warehouses.find(warehouse => warehouse.id === parseInt(this.warehouse_id));

      let priceLevels = JSON.parse(JSON.stringify(warehouse.priceLevels));

      return priceLevels.sort((priceLevel, priceLevel2) => (priceLevel.product.order >= priceLevel2.product.order) ? 1 : -1);
    },
  },
}
</script>

<style scoped>

</style>
