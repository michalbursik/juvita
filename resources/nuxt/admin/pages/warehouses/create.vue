<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">
        <top-panel :title="'Přidat sklad'" backUrl="/warehouses"></top-panel>

        <form @submit.prevent="onSubmit()">
          <div class="card mt-3">
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
  name: "WarehousesCreate",
  components: {TopPanel},
  middleware: ['admin'],
  async fetch() {

  },
  data() {
    return {
      loading: false,
      form: {
        name: '',
      }
    }
  },
  methods: {
    async onSubmit() {
      this.loading = true;

      await this.$store.dispatch('warehouses/store', this.form)
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
