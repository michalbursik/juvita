<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">
        <top-panel :title="'Přidat produkt'" backUrl="/products"></top-panel>

        <form @submit.prevent="onSubmit()">
          <div class="card mt-3">
            <div class="card-body">
              <div class="form-group">
                <div class="row">
                  <div class="col-12 col-md mb-2">
                    <label for="name">Název produktu<span class="text-danger">*</span></label>
                    <input v-model="form.name" class="form-control" type="text" id="name" name="name">

                    <div v-if="hasError('name')" class="invalid-feedback d-block">{{ getError('name') }}</div>
                  </div>
                  <div class="col-12 col-md mb-2">
                    <label for="origin">Původ</label>
                    <input v-model="form.origin" class="form-control" type="text" id="origin" name="origin">

                    <div v-if="hasError('origin')" class="invalid-feedback d-block">{{ getError('origin') }}</div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-md mb-2">
                    <label for="unit">Jednotka<span class="text-danger">*</span></label>

                    <select v-model="form.unit" class="form-control" name="unit" id="unit">
                      <option v-for="unit of getAvailableUnits" :key="unit.value" :value="unit.value">{{ unit.text }}</option>
                    </select>

                    <div v-if="hasError('unit')" class="invalid-feedback d-block">{{ getError('unit') }}</div>
                  </div>
                  <div class="col-12 col-md mb-2">
                    <label for="order">Pořadí<span class="text-danger">*</span></label>
                    <input v-model="form.order" class="form-control" type="number" id="order" name="order">

                    <div v-if="hasError('order')" class="invalid-feedback d-block">{{ getError('order') }}</div>
                  </div>
                  <div class="col-12 col-md mb-2">
                    <label class="d-none d-md-block" for="">&nbsp;</label>

                    <div class="form-control bg-transparent border-0">
                      <input  v-model="form.active" class="form-check-input" type="checkbox" id="active"  name="active" checked>
                      <label class="form-check-label" for="active">Aktivní</label>
                    </div>
                    <div v-if="hasError('active')" class="invalid-feedback d-block">{{ getError('active') }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-primary mt-2">Uložit</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import {mapGetters} from "vuex";
import TopPanel from "~/components/TopPanel";

export default {
  name: "ProductsCreate",
  components: {TopPanel},
  middleware: ['admin'],
  async fetch() {
    // productOrder
    this.form.order = await this.$store.dispatch('products/nextOrder');
  },
  data() {
    return {
      loading: false,
      form: {
        name: '',
        origin: '',
        unit: 'kg',
        order: 0,
        active: true,
      }
    }
  },
  methods: {
    async onSubmit() {
      this.loading = true;

      await this.$store.dispatch('products/store', this.form)
        .then(r => {
          this.$router.push('/products')
        });

      this.loading = false;
    }
  },
  computed: {
    ...mapGetters({
      getError: "validation/getFirst",
      hasError: "validation/exists",
      getAvailableUnits: "options/getProductsUnits"
    })
  },
}
</script>

<style scoped>

</style>
