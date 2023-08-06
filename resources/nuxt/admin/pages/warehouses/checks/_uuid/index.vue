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
                <tr v-for="check of product_checks" :key="`${check.uuid}`">
                  <td>{{ check.product.name }}</td>
                  <td>{{ `${check.amount_before} ${check.product.unit}/${check.amount_after} ${check.product.unit}` }}</td>
                  <td>{{ check.price }} Kč</td>
                  <td>{{ check.amount_before - check.amount_after }} {{ check.product.unit }} {{ `(${(check.amount_before - check.amount_after) * check.price} Kč)`}}</td>
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
    let check = await store.dispatch('checks/fetch', params.uuid);

    const product_checks = await store.dispatch('product_checks/fetchAll', {
      warehouse_uuid: check.warehouse_uuid,
      check_uuid: check.uuid,
      with: 'product'
    })

    return {
      check,
      product_checks: product_checks.data,
    }
  },
  data() {
    return {
      check: null,
    }
  },
  methods: {
    getTotalPrice() {
      return this.product_checks.reduce(
        (carry, value) => carry + (value.amount_before - value.amount_after) * value.price, 0
      ) + this.check.discount
    }
  },
}
</script>

<style scoped>

</style>
