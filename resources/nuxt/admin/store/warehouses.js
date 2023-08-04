export const state = () => ({
  warehouses: [],
});

export const getters = {
  getAll: state => {
    return state.warehouses;
  },
  get: state => warehouse_id => {
    return state.warehouses.find(warehouse => warehouse.id === parseInt(warehouse_id));
  },
};

export const mutations = {
  SET_WAREHOUSES(state, warehouses) {
    state.warehouses = warehouses;
  },
  PUSH_OR_REPLACE_WAREHOUSE(state, new_warehouse) {
    let warehouse_index = state.warehouses.findIndex(warehouse => warehouse.id === parseInt(new_warehouse.id));

    if (warehouse_index >= 0) {
      state.warehouses.splice(warehouse_index, 1, new_warehouse);
    } else {
      state.warehouses.push(new_warehouse);
    }
  },
  REMOVE_WAREHOUSE(state, warehouse_id) {
    let warehouse_index = state.warehouses.findIndex(warehouse => warehouse.id === parseInt(warehouse_id));

    state.warehouses.splice(warehouse_index, 1);
  }
};

export const actions = {
  async fetchAll({commit}, params = null) {
    let warehouses = await this.$axios.$get('warehouses', {
      params: params
    });

    commit('SET_WAREHOUSES', warehouses.data);

    return warehouses;
  },
  async fetch({commit}, warehouse_id) {
    let warehouse = await this.$axios.$get('warehouses/' + warehouse_id);

    commit('PUSH_OR_REPLACE_WAREHOUSE', warehouse.data);

    return warehouse.data;
  },
  async store({commit}, warehouse) {
    warehouse = await this.$axios.$post('warehouses', warehouse);

    commit('PUSH_OR_REPLACE_WAREHOUSE', warehouse.data);

    return warehouse.data;
  },
  async update({commit}, {warehouse, warehouse_id}) {
    warehouse = await this.$axios.$patch('warehouses/' + warehouse_id, warehouse);

    commit('PUSH_OR_REPLACE_WAREHOUSE', warehouse.data);

    return warehouse.data;
  },
  async delete({commit}, warehouse_id) {
    await this.$axios.$delete('warehouses/' + warehouse_id);

    commit('REMOVE_WAREHOUSE', warehouse_id);
  },
  async fetchTotalAmounts({commit}, warehouse_id) {
    const totalAmounts = await this.$axios.$get(`/warehouses/${warehouse_id}/products/total_amount`);

    return totalAmounts.data
  },
  async fetchProductPrices({commit}, {warehouse_uuid, product_uuid}) {
    const productsPrices = await this.$axios.$get(`/warehouses/${warehouse_uuid}/products/${product_uuid}/prices`)

    return productsPrices.data;
  },
  async receiveProduct({commit}, data) {
    await this.$axios.$post('/warehouses/products/receive', data);
  }
}
