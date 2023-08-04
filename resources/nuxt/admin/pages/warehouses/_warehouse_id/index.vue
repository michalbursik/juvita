<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">

        <top-panel :title="currentWarehouse.name" :no-back-button="($auth.user.role === 'employee')" back-url="/warehouses"
                   buttonClass="col-12 col-sm-4 d-flex align-items-center justify-content-end">
          <template slot="buttons">
            <div v-if="isAdmin()">
              <select @change="changeWarehouse()" class="form-control" name="warehouse" id="warehouse">
                <option disabled selected>Přepnout sklad</option>
                <option v-for="warehouse of getAllWarehouses"
                        v-if="warehouse.uuid !== currentWarehouse.uuid"
                        :key="warehouse.uuid"
                        :value="warehouse.uuid"
                >
                  {{ warehouse.name }}
                </option>
              </select>
            </div>
          </template>
        </top-panel>


        <div class="row">
          <div :id="`product_${warehouse_product.uuid}`" v-for="warehouse_product of currentWarehouse.products"
               class="col-6 col-md-4 col-lg-3"
          >
            <div class="card mt-3" :style="`
                background: url(${asset(warehouse_product.image)}) white no-repeat 50% 100%;
                background-size: contain;
                border-bottom-left-radius: 0; border-bottom-right-radius: 0;
              `">
              <nuxt-link :to="`/warehouses/${currentWarehouse.uuid}/products/${warehouse_product.product_uuid}`" style="text-decoration: none;">
                <div class="card-body text-center text-white fw-bold" style="
                                min-height: 140px; font-size: 1.2rem; padding: 0; margin-top: 15px;
                                ">
                  <div style="margin-top: -15px;"
                       :style="highlight(warehouse_product)">
                    {{ `${warehouse_product.name}` }} {{ `(${warehouse_product.total_amount}&nbsp;${warehouse_product.unit})` }}
                  </div>
                </div>
              </nuxt-link>
            </div>
            <div class="row">
              <div class="col text-center">
                <div class="btn-group w-100" role="group" aria-label="Product operations">
                  <nuxt-link v-if="isAdmin() && currentWarehouse.type === 'warehouse'" event="" @click.native="changePage(`/warehouses/${currentWarehouse.uuid}/products/${warehouse_product.product_uuid}/receipt`, warehouse_product.product_uuid)" to="" type="button" class="btn btn-success" style="border-top-left-radius: 0; border-top-right-radius: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-plus text-white" viewBox="0 0 16 16">
                      <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                      <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                  </nuxt-link>

                  <nuxt-link event="" @click.native="changePage(`/warehouses/${currentWarehouse.uuid}/products/${warehouse_product.product_uuid}/transmission`, warehouse_product.product_uuid)" to="" type="button" class="btn btn-warning" style="border-top-left-radius: 0; border-top-right-radius: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-arrow-left-right text-white" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M1 11.5a.5.5 0 0 0 .5.5h11.793l-3.147 3.146a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 11H1.5a.5.5 0 0 0-.5.5zm14-7a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H14.5a.5.5 0 0 1 .5.5z"/>
                    </svg>
                  </nuxt-link>

                  <nuxt-link event="" @click.native="changePage(`/warehouses/${currentWarehouse.uuid}/products/${warehouse_product.product_uuid}/issue`, warehouse_product.product_uuid)" to="" type="button" class="btn btn-danger" style="border-top-left-radius: 0; border-top-right-radius: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-trash text-white" viewBox="0 0 16 16">
                      <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                      <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                    </svg>
                  </nuxt-link>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card mt-5">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table">
                <caption class="caption-top text-black">
                  <strong>Pohyby za poslední týden</strong>
                </caption>
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
                <tr v-for="movement of currentWarehouse.movements" :key="movement.uuid"
                    :class="`${movement.type}-color`">
                  <td>{{ movement.issueWarehouse ? movement.issueWarehouse.name : '-' }}</td>
                  <td>{{ movement.receiptWarehouse ? movement.receiptWarehouse.name : '-' }}</td>
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
          </div>
        </div>
      </div>
    </div>
    <div id="RANDOM"></div>
  </div>
</template>

<script>
import {mapGetters} from "vuex";
import TopPanel from "~/components/TopPanel";

export default {
  name: "WarehousesShow",
  components: {TopPanel},
  scrollToTop: false,
  async asyncData({params, store}) {
    let currentWarehouse = await store.dispatch('warehouses/fetch', params.warehouse_id);

    // let movements = await store.dispatch('movements/fetchAll', {
    //   receipt_warehouse_id: params.warehouse_id,
    //   day: new Date().toUTCString(),
    // })

    return {
      currentWarehouse,
      // movementAmounts: totalAmounts,
      movements: [], // movements.data,
    }
  },
  fetch() {
    this.$store.dispatch('warehouses/fetchAll');
  },
  data() {
    return {
      currentWarehouse: null,
      movementAmounts: [],
      movements: [],
    }
  },
  mounted() {
    this.$nextTick(function () {
      let id = localStorage.getItem('scroll_to_product');

      if (id) {
        // window.scrollTo(0, document.getElementById(id).offsetTop);
      } else {
        window.scrollTo(0, 0);
      }
    })
  },
  methods: {
    highlight(warehouse_product) {
      let highlight = 'background: rgba(100, 100, 100, 0.5);'
      let exists = false;

      exists = this.movements.find(movement => {
        let created_at_utc = new Date(movement.created_at_utc);
        let now = new Date();

        let now_utc = new Date(Date.UTC(
          now.getUTCFullYear(),
          now.getUTCMonth(),
          now.getUTCDate(),
          now.getUTCHours(),
          now.getUTCMinutes(),
          now.getUTCSeconds(),
        ));

        let hours_difference = now_utc.getHours() - created_at_utc.getHours()

        console.log(hours_difference);

        return movement.product.uuid === warehouse_product.product_uuid && hours_difference < 4;
      })

      if (exists) {
        highlight = 'background: rgba(0, 130, 0, 0.8);'
      }

      return highlight;
    },
    changePage(url, product_id) {
      localStorage.setItem('scroll_to_product', `product_${product_id}`);

      this.$router.push(url);
    },
    isAdmin() {
      return this.$auth.user.role === 'admin';
    },
    changeWarehouse() {
      let el = document.getElementById('warehouse');

      this.$router.push('/warehouses/' + el.value)

    }
  },
  computed: {
    ...mapGetters({
      getAllWarehouses: "warehouses/getAll",
    })
  },
}
</script>

<style scoped>

</style>
