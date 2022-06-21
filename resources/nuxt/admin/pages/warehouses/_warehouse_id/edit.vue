<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">
        <div class="row mb-3">
          <div class="col">
            <h1 class="h1">Změnit sklad - {{ warehouse.name }}</h1>
          </div>
        </div>

        <form @submit.prevent="onSubmit()">
          <div class="card">
            <div class="card-body">
              <div class="form-group">
                <div class="row">
                  <div class="col-12 col-md mb-2">
                    <label for="name">Název skladu<span class="text-danger">*</span></label>
                    <input v-model="form.name" class="form-control" type="text" id="name" name="name">

                    <div v-if="hasError('name')" class="invalid-feedback d-block">{{ getError('name') }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-primary mt-2">Upravit</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
  name: "WarehouseEdit",
  middleware: ['admin'],
  async asyncData({params, store}) {
    let warehouse = await store.dispatch('warehouses/fetch', params.warehouse_id);

      return {
        warehouse,
        form: {
          name: warehouse.name,
        }
      }
  },
  data() {
    return {
      loading: false,
      warehouse: null,
      form: {
        name: '',
      }
    }
  },
  methods: {
    async onSubmit() {
      this.loading = true;

      await this.$store.dispatch('warehouses/update', {
        warehouse: this.form,
        warehouse_id: this.$route.params.warehouse_id
      })
      .then(r => {
        this.$router.push('/warehouses')
      });

      this.loading = false;
    }
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
