<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">
        <top-panel :title="'Přehled všech pohybů'"></top-panel>

        <div class="row">
          <div class="col-12 col-md mt-2">
            <label for="products"><strong>Produkt</strong></label>
            <select v-model="filters.product_uuid" name="products" id="products" class="form-control">
              <option value="">Všechny produkty</option>
              <option v-for="product of products" :key="product.uuid" :value="product.uuid">{{ product.name }}</option>
            </select>
          </div>
          <div v-if="!($auth.user.role === 'employee')" class="col-12 col-md mt-2">
            <label for="types"><strong>Typ</strong></label>
            <select v-model="filters.type" name="types" id="types" class="form-control">
              <option value="">Všechny typy</option>
              <option v-for="type of types" :key="type.value" :value="type.value">{{ type.text }}</option>
            </select>
          </div>
          <div class="col-12 col-md mt-2">
            <label for="users"><strong>Uživatel</strong></label>
            <select v-model="filters.user_uuid" name="users" id="users" class="form-control">
              <option value="">Všechny uživatele</option>
              <option v-for="user of users" :key="user.uuid" :value="user.uuid">{{ user.name }}</option>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-12 col-md mt-2">
            <label for="source_warehouse_uuid"><strong>Výdejní sklad</strong></label>
            <select v-model="filters.source_warehouse_uuid" name="source_warehouse_uuid" id="source_warehouse_uuid" class="form-control">
              <option value="">Všechny výdejní sklady</option>
              <option v-for="warehouse of warehouses" :key="warehouse.uuid" :value="warehouse.uuid">{{ warehouse.name }}</option>
            </select>
          </div>
          <div class="col-12 col-md mt-2">
            <label for="target_warehouse_uuid"><strong>Příjmový sklad</strong></label>
            <select :disabled="($auth.user.role === 'employee')" v-model="filters.target_warehouse_uuid" name="target_warehouse_uuid" id="target_warehouse_uuid" class="form-control">
              <option value="">Všechny příjmové sklady</option>
              <option v-for="warehouse of warehouses" :key="warehouse.uuid" :value="warehouse.uuid">{{ warehouse.name }}</option>
            </select>
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
                <tr v-for="movement of getMovements" :key="movement.uuid" :class="`${movement.type}-color`">
                  <td>{{ movement.sourceWarehouse ? movement.sourceWarehouse.name : '-' }}</td>
                  <td>{{ movement.targetWarehouse ? movement.targetWarehouse.name : '-' }}</td>
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
  name: "MovementsIndex",
  components: {TopPanel, Pagination},
  async asyncData({params, store}) {
    let r = await store.dispatch('movements/fetchAll');
    let warehouses = await store.dispatch('warehouses/fetchAll', {
      perPage: 1000,
    });

    return {
      pagination: r.pagination,
      warehouses: warehouses.data
    }
  },
  fetch() {
    this.$store.dispatch('products/fetchAll', {
      perPage: 1000
    });
    this.$store.dispatch('users/fetchAll');
  },
  mounted() {
    if (this.$auth.user.role === 'employee') {
      this.filters.target_warehouse_uuid = this.$auth.user.warehouse_uuid;
    }
  },
  data() {
    return {
      pagination: null,
      warehouses: [],
      filters: {
        product_uuid: "",
        type: "",
        user_uuid: "",
        source_warehouse_uuid: "",
        target_warehouse_uuid: "",
      }
    }
  },
  computed: {
    ...mapGetters({
      getMovements: 'movements/getAll',
      users: 'users/getAll',
      products: 'products/getAll',
      types: 'options/getMovementTypes',
    })
  },
  methods: {
    async reloadData(pagination) {
      let r = await this.$store.dispatch('movements/fetchAll', {...pagination, ...this.filters});

      this.pagination = r.pagination;
    }
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
