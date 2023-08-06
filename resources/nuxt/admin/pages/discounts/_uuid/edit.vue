<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">
        <div class="row mb-3">
          <div class="col">
            <h1 class="h1">Změnit slevu</h1>
          </div>
        </div>

        <form @submit.prevent="onSubmit()">
          <div class="card">
            <div class="card-body">
              <div class="form-group">
                <div class="row">
                  <div class="col-12 col-md-6 mb-2">
                    <label for="amount">Množství<span class="text-danger">*</span></label>
                    <input v-model="form.amount" class="form-control" type="text" id="amount" name="amount">

                    <div v-if="hasError('amount')" class="invalid-feedback d-block">{{ getError('amount') }}</div>
                  </div>
                  <div class="col-12 col-md-6 mb-2">
                    <label for="warehouse_uuid">Sklad<span class="text-danger">*</span></label>

                    <select v-model="form.warehouse_uuid" class="form-control" name="warehouse_uuid" id="warehouse_uuid">
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
            Upravit
          </button>

          <button @click.prevent="deleteDiscount()" :disabled="loading" type="button" class="btn btn-danger mt-2">
            <span v-if="loading" class="spinner-border spinner-border-sm text-light me-1" role="status"></span>
            Smazat
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
  name: "DiscountsEdit",
  middleware: ['admin'],
  async asyncData({params, store}) {
      let discount = await store.dispatch('discounts/fetch', params.uuid);
      let warehouses = await store.dispatch('warehouses/fetchAll');

      return {
        discount,
        warehouses: warehouses.data,
        form: {
          amount: discount.amount,
          note: discount.note,
          warehouse_uuid: discount.warehouse.uuid,
        }
      }
  },
  data() {
    return {
      loading: false,
      discount: null,
      warehouses: [],
      form: {
        amount: '',
        note: '',
        warehouse_uuid: null,
      }
    }
  },
  methods: {
    async deleteDiscount() {
      this.loading = true;
      await this.$store.dispatch('discounts/delete', this.$route.params.uuid)
        .then(r => {
          this.$router.push('/discounts')
        });

      this.loading = false;
    },
    async onSubmit() {
      this.loading = true;

      await this.$store.dispatch('discounts/update', {
        discount: this.form,
        discount_uuid: this.$route.params.uuid
      })
      .then(r => {
        this.$router.push('/discounts')
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
