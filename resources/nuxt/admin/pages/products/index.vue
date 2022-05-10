<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">
        <top-panel :title="'Produkty'">
          <template slot="buttons">
            <nuxt-link to="/products/create" class="btn btn-primary">Přidat</nuxt-link>
          </template>
        </top-panel>

        <div class="card mt-3">
          <div class="card-body">
            <table class="table">
              <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Název (Jednotka)</th>
                <th scope="col" class="d-none d-md-table-cell">Původ</th>
                <th scope="col" class="d-none d-md-table-cell">Pořadí</th>
                <th scope="col">Aktivní</th>
                <th scope="col" class="d-none d-md-table-cell">Obrázek</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="product of products" :key="product.id" style="cursor: pointer;" @click="$router.push(`/products/${product.id}/edit`)">
                <td>{{ product.id }}</td>
                <td>{{ product.name }} ({{ product.unit }})</td>
                <td class="d-none d-md-table-cell">{{ product.origin }}</td>
                <td class="d-none d-md-table-cell">{{ product.order }}</td>
                <td>
                  <svg v-if="product.active" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg text-success" viewBox="0 0 16 16">
                    <path d="M12.736 3.97a.733.733 0
                    0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                  </svg>

                  <svg v-else xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x text-danger" viewBox="0 0 16 16">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                  </svg>
                </td>
                <td class="d-none d-md-table-cell">
                  <img style="max-width: 100px; max-height: 100px" :src="asset(product.image)" alt=""></td>
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
  middleware: ['admin'],
  async asyncData({params, store}) {
      let products = await store.dispatch('products/fetchAll', {
        perPage: 1000,
        orderBy: 'order'
      });

      return {
          products: products.data
      }
  },
  data() {
    return {
      products: null,
    }
  },
}
</script>

<style scoped>

</style>
