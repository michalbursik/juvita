export const state = () => ({
    overviews: [],
});

export const getters = {
    getAll: state => {
        return state.overviews;
    },
    get: state => overview_id => {
        return state.overviews.find(overview => overview.id === parseInt(overview_id));
    },
};

export const mutations = {
    SET_OVERVIEWS(state, overviews) {
        state.overviews = overviews;
    },
    PUSH_OR_REPLACE_OVERVIEW(state, new_overview) {
        let overview_index = state.overviews.findIndex(overview => overview.id === parseInt(new_overview.id));

        if (overview_index >= 0) {
            state.overviews.splice(overview_index, 1, new_overview);
        } else {
            state.overviews.push(new_overview);
        }
    },
    REMOVE_OVERVIEW(state, overview_id) {
        let overview_index = state.overviews.findIndex(overview => overview.id === parseInt(overview_id));

        state.overviews.splice(overview_index, 1);
    }
};

export const actions = {
    async fetchAll({ commit }, params = null) {
        let overviews = await this.$axios.$get('overviews', {
            params: params
        });

        commit('SET_OVERVIEWS', overviews.data);

        return overviews;
    },
    async fetch({ commit }, overview_id) {
        let overview = await this.$axios.$get('overviews/' + overview_id);

        commit('PUSH_OR_REPLACE_OVERVIEW', overview.data);

        return overview.data;
    },
    async store({ commit }, overview) {
        overview = await this.$axios.$post('overviews', overview);

        commit('PUSH_OR_REPLACE_OVERVIEW', overview.data);

        return overview.data;
    },
    async update({ commit }, { overview, overview_id }) {
        overview = await this.$axios.$patch('overviews/' + overview_id, overview);

        commit('PUSH_OR_REPLACE_OVERVIEW', overview.data);

        return overview.data;
    },
    async delete({ commit }, overview_id) {
        await this.$axios.$delete('overviews/' + overview_id);

        commit('REMOVE_OVERVIEW', overview_id);
    },
}
