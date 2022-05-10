export const state = () => ({
    priceLevels: [],
});

export const getters = {
    getAll: state => {
        return state.priceLevels;
    },
    get: state => priceLevel_id => {
        return state.priceLevels.find(priceLevel => priceLevel.id === parseInt(priceLevel_id));
    },
};

export const mutations = {
    SET_PRICELEVELS(state, priceLevels) {
        state.priceLevels = priceLevels;
    },
    PUSH_OR_REPLACE_PRICELEVEL(state, new_priceLevel) {
        let priceLevel_index = state.priceLevels.findIndex(priceLevel => priceLevel.id === parseInt(new_priceLevel.id));

        if (priceLevel_index >= 0) {
            state.priceLevels.splice(priceLevel_index, 1, new_priceLevel);
        } else {
            state.priceLevels.push(new_priceLevel);
        }
    },
    REMOVE_PRICELEVEL(state, priceLevel_id) {
        let priceLevel_index = state.priceLevels.findIndex(priceLevel => priceLevel.id === parseInt(priceLevel_id));

        state.priceLevels.splice(priceLevel_index, 1);
    }
};

export const actions = {
    async fetchAll({ commit }, params = null) {
        let priceLevels = await this.$axios.$get('priceLevels', {
            params: params
        });

        commit('SET_PRICELEVELS', priceLevels.data);

        return priceLevels;
    },
    async fetch({ commit }, priceLevel_id) {
        let priceLevel = await this.$axios.$get('priceLevels/' + priceLevel_id);

        commit('PUSH_OR_REPLACE_PRICELEVEL', priceLevel.data);

        return priceLevel.data;
    },
    async store({ commit }, priceLevel) {
        priceLevel = await this.$axios.$post('priceLevels', priceLevel);

        commit('PUSH_OR_REPLACE_PRICELEVEL', priceLevel.data);

        return priceLevel.data;
    },
    async update({ commit }, { priceLevel, priceLevel_id }) {
        priceLevel = await this.$axios.$patch('priceLevels/' + priceLevel_id, priceLevel);

        commit('PUSH_OR_REPLACE_PRICELEVEL', priceLevel.data);

        return priceLevel.data;
    },
    async delete({ commit }, priceLevel_id) {
        await this.$axios.$delete('priceLevels/' + priceLevel_id);

        commit('REMOVE_PRICELEVEL', priceLevel_id);
    },
}
