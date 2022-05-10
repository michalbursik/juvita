<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">

        <top-panel :title="`Kontrola ze dne: ${check.created_at}`"></top-panel>

        <div class="card mt-3">
          <div class="card-body">
            <div class="">
              <table class="table">
                <thead>
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Množství před/Množství po</th>
                  <th scope="col">Cena za jednotku</th>
                  <th scope="col">Prodáno</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="product of check.products" :key="`${product.id}${product.price}`">
                  <td>{{ product.name }}</td>
                  <td>{{ `${product.amount_before} ${product.unit}/${product.amount_after} ${product.unit}` }}</td>
                  <td>{{ product.price }} Kč</td>
                  <td>{{ product.amount_before - product.amount_after }} {{ product.unit }} {{ `(${(product.amount_before - product.amount_after) * product.price} Kč)`}}</td>
                </tr>
                <tr v-if="check.discount">
                  <td colspan="3">Sleva</td>
                  <td>{{ check.discount }} Kč</td>
                </tr>
                <tr>
                  <td style="border-top-width: 2px" class="border-top border-dark" colspan="3"><strong>Celkem</strong></td>
                  <td style="border-top-width: 2px" class="border-top border-dark">{{ getTotalPrice() }} Kč</td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import TopPanel from "~/components/TopPanel";
import Pagination from "~/components/Pagination";

export default {
  name: "WarehouseChecksIndex",
  middleware: ['admin'],
  components: {TopPanel, Pagination},
  async asyncData({params, store}) {
    let check = await store.dispatch('checks/fetch', params.id);

    return {
      check
    }
  },
  data() {
    return {
      check: null,
    }
  },
  methods: {
    getTotalPrice() {
      return this.check.products.reduce((previousValue, currentValue) => previousValue + ((currentValue.amount_before - currentValue.amount_after) * currentValue.price), 0) + this.check.discount;
    }
  },
}
</script>

<style scoped>

</style>
