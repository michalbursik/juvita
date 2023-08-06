export const state = () => ({
    product_checks: [],
});

export const getters = {
    getAll: state => {
        return state.product_checks;
    },
    get: state => product_check_id => {
        return state.product_checks.find(product_check => product_check.id === parseInt(product_check_id));
    },
};

export const mutations = {
    SET_PRODUCT_CHECKS(state, product_checks) {
        state.product_checks = product_checks;
    },
    PUSH_OR_REPLACE_PRODUCT_CHECK(state, new_product_check) {
        let product_check_index = state.product_checks.findIndex(product_check => product_check.id === parseInt(new_product_check.id));

        if (product_check_index >= 0) {
            state.product_checks.splice(product_check_index, 1, new_product_check);
        } else {
            state.product_checks.push(new_product_check);
        }
    },
    REMOVE_PRODUCT_CHECK(state, product_check_id) {
        let product_check_index = state.product_checks.findIndex(product_check => product_check.id === parseInt(product_check_id));

        state.product_checks.splice(product_check_index, 1);
    }
};

export const actions = {
    async fetchAll({ commit }, params = null) {
        let product_checks = await this.$axios.$get('product_checks', {
            params: params
        });

        commit('SET_PRODUCT_CHECKS', product_checks.data);

        return product_checks;
    },
    async fetch({ commit }, product_check_id) {
        let product_check = await this.$axios.$get('product_checks/' + product_check_id);

        commit('PUSH_OR_REPLACE_PRODUCT_CHECK', product_check.data);

        return product_check.data;
    },
    async store({ commit }, product_check) {
        product_check = await this.$axios.$post('product_checks', product_check);

        commit('PUSH_OR_REPLACE_PRODUCT_CHECK', product_check.data);

        return product_check.data;
    },
    async update({ commit }, { product_check, product_check_id }) {
        product_check = await this.$axios.$patch('product_checks/' + product_check_id, product_check);

        commit('PUSH_OR_REPLACE_PRODUCT_CHECK', product_check.data);

        return product_check.data;
    },
    async delete({ commit }, product_check_id) {
        await this.$axios.$delete('product_checks/' + product_check_id);

        commit('REMOVE_PRODUCT_CHECK', product_check_id);
    },
}
