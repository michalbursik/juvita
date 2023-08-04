<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">

        <top-panel
          :title="`${warehouse.name} - příjemka`"
          title-class="text-success"
          :back-url="`/warehouses/${$route.params.warehouse_id}`"></top-panel>

        <form @submit.prevent="onSubmit()" id="movements_form">

          <div class="card mt-3">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <label for="amount">Množství ({{ warehouse_product.unit }})</label>
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
                  <label for="price">Cena</label>
                  <input
                    @click="setCurrentInput('price')"
                    class="form-control"
                    type="number"
                    id="price"
                    name="price"
                    v-model="form.price"
                    readonly
                  >

                  <div v-if="hasError('price')" v-html="getError('price')" class="invalid-feedback d-block"></div>
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
import TopPanel from "~/components/TopPanel";
import NumericPad from "~/components/NumericPad";

export default {
  name: "WarehouseProductReceipt",
  components: {TopPanel, NumericPad},
  middleware: ['admin'],
  async asyncData({params, store}) {
    let warehouse = await store.dispatch('warehouses/fetch', params.warehouse_id);
    let warehouse_product = await store.dispatch('warehouses/fetchProductPrices', {
      warehouse_uuid: params.warehouse_id,
      product_uuid: params.product_id,
    })

    return {
      warehouse,
      warehouse_product
    }
  },
  mounted() {
    // Load last one => just for easier input
    const maxAmount = Math.max(...this.warehouse_product.prices.map(p => p.amount));
    this.form.price = this.warehouse_product.prices.filter(function (price) {
      return price.amount === maxAmount
    })[0].price;


    this.form.product_uuid = this.warehouse_product.product_uuid;
    this.form.warehouse_uuid = this.warehouse_product.warehouse_uuid;
  },
  data() {
    return {
      warehouse: null,
      warehouse_product: null,
      loading: false,
      form: {
        amount: 0,
        price: 0,
        warehouse_uuid: null,
        product_uuid: null,
        // user_id: this.$auth.user.id,
        // type: this.getType(),
      }
    }
  },
  methods: {
    updateValue(val) {
      this.form[val.type] = val.value;
    },
    async onSubmit() {
      this.loading = true;

      await this.$store.dispatch('warehouses/receiveProduct', this.form)
        .then(r => {
          this.$router.push(`/warehouses/${this.$route.params.warehouse_id}`)
        }).catch(r => {
          console.log(r);
          this.loading = false;
        });

    },
    setCurrentInput(type) {
      this.$refs.num_pad.setCurrentInput(type);
    },
    // getType() {
    //   let url = this.$nuxt.$route.path;
    //
    //   return url.split('/').at(-1);
    // }
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
