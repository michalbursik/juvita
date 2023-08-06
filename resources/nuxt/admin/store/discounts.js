export const state = () => ({
    discounts: [],
});

export const getters = {
    getAll: state => {
        return state.discounts;
    },
    get: state => discount_id => {
        return state.discounts.find(discount => discount.id === parseInt(discount_id));
    },
};

export const mutations = {
    SET_DISCOUNTS(state, discounts) {
        state.discounts = discounts;
    },
    PUSH_OR_REPLACE_DISCOUNT(state, new_discount) {
        let discount_index = state.discounts.findIndex(discount => discount.id === parseInt(new_discount.id));

        if (discount_index >= 0) {
            state.discounts.splice(discount_index, 1, new_discount);
        } else {
            state.discounts.push(new_discount);
        }
    },
    REMOVE_DISCOUNT(state, discount_id) {
        let discount_index = state.discounts.findIndex(discount => discount.id === parseInt(discount_id));

        state.discounts.splice(discount_index, 1);
    }
};

export const actions = {
    async fetchAll({ commit }, params = null) {
        let discounts = await this.$axios.$get('discounts', {
            params: params
        });

        commit('SET_DISCOUNTS', discounts.data);

        return discounts;
    },
    async fetch({ commit }, discount_uuid) {
        let discount = await this.$axios.$get('discounts/' + discount_uuid);

        commit('PUSH_OR_REPLACE_DISCOUNT', discount.data);

        return discount.data;
    },
    async store({ commit }, discount) {
        discount = await this.$axios.$post('discounts', discount);

        commit('PUSH_OR_REPLACE_DISCOUNT', discount.data);

        return discount.data;
    },
    async update({ commit }, { discount, discount_uuid }) {
        discount = await this.$axios.$patch('discounts/' + discount_uuid, discount);

        commit('PUSH_OR_REPLACE_DISCOUNT', discount.data);

        return discount.data;
    },
    async delete({ commit }, discount_uuid) {
        await this.$axios.$delete('discounts/' + discount_uuid);

        commit('REMOVE_DISCOUNT', discount_uuid);
    },
}
