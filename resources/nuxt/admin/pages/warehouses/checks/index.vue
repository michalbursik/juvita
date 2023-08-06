<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">

        <top-panel :title="'Přehled všech kontrol'">
          <template slot="buttons">
            <nuxt-link to="/warehouses/checks/create" class="btn btn-primary">Přidat</nuxt-link>
          </template>
        </top-panel>

        <div class="card mt-3">
          <div class="card-body">
            <div class="">
              <table class="table">
                <thead>
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Sklad</th>
                  <th scope="col">Datum</th>
                  <th scope="col">Kontrolor</th>
                </tr>
                </thead>
                <tbody>
                <tr style="cursor: pointer;" @click="$router.push(`/warehouses/checks/${check.uuid}`)" v-for="check of getChecks" :key="check.uuid">
                  <td>{{ check.id }}</td>
                  <td>{{ check.warehouse.name }}</td>
                  <td>{{ check.created_at }}</td>
                  <td>{{ check.user.name }}</td>
                </tr>
                </tbody>
              </table>
            </div>

            <pagination :pagination="pagination" @change="reloadData"></pagination>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapGetters} from "vuex";
import TopPanel from "~/components/TopPanel";
import Pagination from "~/components/Pagination";

export default {
  name: "WarehouseChecksIndex",
  middleware: ['admin'],
  components: {TopPanel, Pagination},
  async asyncData({params, store}) {
    let r = await store.dispatch('checks/fetchAll', {
      with: ['user', 'warehouse']
    });

    return {
      pagination: r.pagination
    }
  },
  data() {
    return {
      pagination: null,
      filters: {}
    }
  },
  methods: {
    async reloadData(pagination) {
      let r = await this.$store.dispatch('movements/fetchAll', {
        ...pagination, ...this.filters,
        with: ['user', 'warehouse']
      });

      this.pagination = r.pagination;
    }
  },
  computed: {
    ...mapGetters({
      getChecks: 'checks/getAll'
    })
  },
  watch: {
    filters: {
      deep: true,
      handler() {
        this.reloadData({currentPage: this.pagination.currentPage})
      }
    }
  },
}
</script>

<style scoped>

</style>
