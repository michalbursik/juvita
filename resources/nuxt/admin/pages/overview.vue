<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-12">
        <top-panel :title="'Přehled zboží'"></top-panel>

        <div class="row">
          <div class="col-12 col-md mt-2">
            <label for="date_from"><strong>Datum od</strong></label>
            <date-picker format="dd. MM. yyyy" v-model="filters.date_from"></date-picker>
          </div>
          <div class="col-12 col-md mt-2">
            <label for="date_to"><strong>Datum do</strong></label>
            <date-picker format="dd. MM. yyyy" v-model="filters.date_to"></date-picker>
          </div>
        </div>

        <div class="card mt-3">
          <div class="card-body">
            <table class="table">
              <thead>
              <tr>
                <th scope="col">Název produktu</th>
                <th scope="col">Sklad A</th>
                <th scope="col">Sklad B</th>
                <th scope="col">Sklad C</th>
                <th scope="col">Sklad D</th>
                <th scope="col">Sklad Odpad</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="overview of overviews">
<!--                <td>{{ `${overview.product_name} (Kč)` }}</td>-->
<!--                <td v-for="warehouse of overview.warehouses">-->
<!--&lt;!&ndash;                  {{ warehouse.amount }}&ndash;&gt;-->
<!--                </td>-->
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
import DatePicker from "~/components/DatePicker";

export default {
  name: "OverviewIndex",
  components: {TopPanel, DatePicker},
  async fetch() {
    let overview = await this.$store.dispatch('overviews/fetchAll', {
        date_from: this.filters.date_from,
        date_to: this.filters.date_to,
      });

    this.overviews = overview.data;
  },
  data() {
    return {
      overviews: [],
      filters: {
        date_from: this.getCurrentDate(7),
        date_to: this.getCurrentDate(),
      }
    }
  },
  methods: {
    getCurrentDate(subDays = 0) {
      let now = new Date();

      if (subDays > 0) {
        now.setDate(now.getDate() - subDays)
      }

      return now;
    }
  },
}
</script>

<style scoped>

</style>
