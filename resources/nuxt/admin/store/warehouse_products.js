export const state = () => ({});

export const getters = {};

export const mutations = {};

export const actions = {
  async fetchTotalAmounts({commit}, warehouse_id) {
    const totalAmounts = await this.$axios.$get(`/warehouse_products/${warehouse_id}/products/total_amount`);

    return totalAmounts.data
  },
  async fetch({commit}, warehouse_product_uui) {
    const warehouse_product = await this.$axios.$get(`/warehouse_products/${warehouse_product_uui}`)

    return warehouse_product.data;
  },
  async receive({commit}, data) {
    await this.$axios.$post('/warehouse_products/receive', data);
  },
  async move({commit}, data) {
    await this.$axios.$post('/warehouse_products/move', data);
  },
  async trash({commit}, data) {
    await this.$axios.$post('/warehouse_products/trash', data);
  },
}
