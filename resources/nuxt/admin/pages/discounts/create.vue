<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">
        <top-panel :title="'Přidat slevu'" backUrl="/discounts"></top-panel>

        <form @submit.prevent="onSubmit()">
          <div class="card mt-3">
            <div class="card-body">
              <div class="form-group">
                <div class="row">
                  <div class="col-12 col-md-6 mb-2">
                    <label for="amount">Sleva<span class="text-danger">*</span></label>
                    <input v-model="form.amount" class="form-control" type="text" id="amount" name="amount">

                    <div v-if="hasError('amount')" class="invalid-feedback d-block">{{ getError('amount') }}</div>
                  </div>
                  <div class="col-12 col-md-6 mb-2">
                    <label for="unit">Sklad<span class="text-danger">*</span></label>

                    <select v-model="form.warehouse_uuid" class="form-control" name="unit" id="unit">
                      <option v-for="warehouse of warehouses" :key="warehouse.uuid" :value="warehouse.uuid">{{ warehouse.name }}</option>
                    </select>

                    <div v-if="hasError('warehouse_uuid')" class="invalid-feedback d-block">{{ getError('warehouse_uuid') }}</div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <label for="note">Poznámka</label>
                    <textarea v-model="form.note" class="form-control" name="note" id="note" cols="30" rows="5"></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <button :disabled="loading" type="submit" class="btn btn-primary mt-2">
            <span v-if="loading" class="spinner-border spinner-border-sm text-light me-1" role="status"></span>
            Uložit
          </button>
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
  async asyncData({params, store}) {
      let warehouses = await store.dispatch('warehouses/fetchAll');

      return {
          warehouses: warehouses.data
      }
  },
  mounted() {
    this.form.warehouse_uuid = this.$auth.user.warehouse_uuid;
  },
  data() {
    return {
      loading: false,
      warehouses: [],
      form: {
        amount: '',
        note: '',
        warehouse_uuid: null,
      }
    }
  },
  methods: {
    async onSubmit() {
      this.loading = true;

      await this.$store.dispatch('discounts/store', this.form)
        .then(r => {
          this.$router.push('/discounts')
        }).catch(e => {
          this.loading = false;
        });
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
