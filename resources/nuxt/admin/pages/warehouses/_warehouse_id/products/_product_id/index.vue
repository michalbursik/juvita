<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">
        <top-panel
          :title="`${warehouse.name} - ${product.name}`"
          title-class="text-warning"
          :back-url="`/warehouses/${$route.params.warehouse_id}`"></top-panel>

        <div v-if="priceLevels" class="card mt-3">
          <div class="card-header">{{ product.name }} - Cenové hladiny </div>
          <div class="card-body">
            <div class="row">
              <div class="col"><strong>Počet</strong></div>
              <div class="col"><strong>Cena</strong></div>
            </div>
            <div v-for="priceLevel of priceLevels" class="row">
              <div class="col">
                {{ priceLevel.amount }} {{ product.unit }}
              </div>
              <div class="col">
                {{ priceLevel.price }} Kč
              </div>
            </div>
          </div>
        </div>

        <div class="card mt-3">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table">
                <thead>
                <tr>
                  <th scope="col">Výdejní sklad</th>
                  <th scope="col">Příjmový sklad</th>
                  <th scope="col">Produkt</th>
                  <th scope="col">Typ</th>
                  <th scope="col">Počet</th>
                  <th scope="col">Cena</th>
                  <th scope="col">Uživatel</th>
                  <th scope="col">Vytvořeno</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="movement of movements"
                    :key="movement.id"
                    :class="`${movement.type}-color`">
                  <td>{{ movement.issueWarehouse ? movement.issueWarehouse.name : '-' }}</td>
                  <td>{{ movement.receiptWarehouse ? movement.receiptWarehouse.name : '-' }}</td>
                  <td>{{ movement.product.name }}</td>
                  <td>{{ movement.translated_type }}</td>
                  <td>{{ `${movement.amount}&nbsp;${movement.product.unit}` }}</td>
                  <td>{{ movement.price }}&nbsp;Kč</td>
                  <td>{{ movement.user.name }}</td>
                  <td>{{ movement.created_at }}</td>
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

export default {
  name: "WarehousesProductsShow",
  components: {TopPanel},
  async asyncData({params, store}) {
      let warehouse = await store.dispatch('warehouses/fetch', params.warehouse_id);
      let movements = await store.dispatch('movements/fetchAll', {
        warehouse_id: params.warehouse_id,
        product_id: params.product_id
      });
      let product = await store.dispatch('products/fetch', params.product_id);

      let priceLevels = await store.dispatch('priceLevels/fetchAll', {
        warehouse_id: params.warehouse_id,
        product_id: params.product_id
      });

      return {
        product,
        warehouse,
        movements: movements.data, // todo pagination
        priceLevels: priceLevels.data,
      }
  },
  data() {
    return {
      warehouse: null,
      product: null,
      movements: [],
      priceLevels: [],
    }
  },
  methods: {
    changeWarehouse() {
      let el = document.getElementById('warehouse');

      this.$router.push('/warehouses/' + el.value)
    }
  },
}
</script>

<style scoped>

</style>
