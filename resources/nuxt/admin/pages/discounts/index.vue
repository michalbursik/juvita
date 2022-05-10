<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">
        <top-panel :title="'Slevy'">
          <template slot="buttons">
            <nuxt-link to="/discounts/create" class="btn btn-primary">Přidat</nuxt-link>
          </template>
        </top-panel>

        <div class="card mt-3">
          <div class="card-body">
            <table class="table">
              <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Sklad</th>
                <th scope="col">Uživatel</th>
                <th scope="col">Množství</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="discount of discounts" :key="discount.id"
                  :style="$auth.user.role === 'admin' ? 'cursor: pointer': ''"
                  @click="$auth.user.role === 'admin' ? $router.push(`/discounts/${discount.id}/edit`) : null">
                <td>{{ discount.id }}</td>
                <td>{{ discount.warehouse.name }}</td>
                <td>{{ discount.user.name }}</td>
                <td>{{ discount.amount }} Kč</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import TopPanel from "~/components/TopPanel";

export default {
  name: "ProductsIndex",
  components: {TopPanel},
  async asyncData({params, store}) {
      let discounts = await store.dispatch('discounts/fetchAll');

      return {
          discounts: discounts.data
      }
  },
  data() {
    return {
      discounts: null,
    }
  },
}
</script>

<style scoped>

</style>
