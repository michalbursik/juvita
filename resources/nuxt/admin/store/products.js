export const state = () => ({
    products: [],
});

export const getters = {
    getAll: state => {
        return state.products;
    },
    get: state => product_id => {
        return state.products.find(product => product.id === parseInt(product_id));
    },
};

export const mutations = {
    SET_PRODUCTS(state, products) {
        state.products = products;
    },
    PUSH_OR_REPLACE_PRODUCT(state, new_product) {
        let product_index = state.products.findIndex(product => product.id === parseInt(new_product.id));

        if (product_index >= 0) {
            state.products.splice(product_index, 1, new_product);
        } else {
            state.products.push(new_product);
        }
    },
    REMOVE_PRODUCT(state, product_id) {
        let product_index = state.products.findIndex(product => product.id === parseInt(product_id));

        state.products.splice(product_index, 1);
    }
};

export const actions = {
    async fetchAll({ commit }, params = null) {
        let products = await this.$axios.$get('products', {
            params: params
        });

        commit('SET_PRODUCTS', products.data);

        return products;
    },
    async fetch({ commit }, product_uuid) {
        let product = await this.$axios.$get('products/' + product_uuid);

        commit('PUSH_OR_REPLACE_PRODUCT', product.data);

        return product.data;
    },
    async store({ commit }, product) {
        product = await this.$axios.$post('products', product);

        commit('PUSH_OR_REPLACE_PRODUCT', product.data);

        return product.data;
    },
    async update({ commit }, { product, product_uuid }) {
        product = await this.$axios.$patch('products/' + product_uuid, product);

        commit('PUSH_OR_REPLACE_PRODUCT', product.data);

        return product.data;
    },
    async delete({ commit }, product_uuid) {
        await this.$axios.$delete('products/' + product_uuid);

        commit('REMOVE_PRODUCT', product_id);
    },
  async nextOrder({ commit }) {
    let order = await this.$axios.$get('products/nextOrder');
    return order.data.order;
  }
}
