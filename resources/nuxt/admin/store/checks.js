export const state = () => ({
    checks: [],
});

export const getters = {
    getAll: state => {
        return state.checks;
    },
    get: state => check_id => {
        return state.checks.find(check => check.id === parseInt(check_id));
    },
};

export const mutations = {
    SET_CHECKS(state, checks) {
        state.checks = checks;
    },
    PUSH_OR_REPLACE_CHECK(state, new_check) {
      let check_index = state.checks.findIndex(check => check.id === parseInt(new_check.id));

      if (check_index >= 0) {
            state.checks.splice(check_index, 1, new_check);
        } else {
            state.checks.push(new_check);
        }
    },
    REMOVE_CHECK(state, check_id) {
        let check_index = state.checks.findIndex(check => check.id === parseInt(check_id));

        state.checks.splice(check_index, 1);
    }
};

export const actions = {
    async fetchAll({ commit }, params = null) {
        let checks = await this.$axios.$get('checks', {
            params: params
        });

      commit('SET_CHECKS', checks.data);

        return checks;
    },
    async fetchAllProductPrices({ commit }, params = null) {
      return await this.$axios.$get('checks/products', {
          params: params
        });
    },
    async fetch({ commit }, check_id) {
        let check = await this.$axios.$get('checks/' + check_id);

      commit('PUSH_OR_REPLACE_CHECK', check.data);

        return check.data;
    },
    async store({ commit }, check) {
        check = await this.$axios.$post('checks', check);

        commit('PUSH_OR_REPLACE_CHECK', check.data);

        return check.data;
    },
    async update({ commit }, { check, check_id }) {
        check = await this.$axios.$patch('checks/' + check_id, check);

        commit('PUSH_OR_REPLACE_CHECK', check.data);

        return check.data;
    },
    async delete({ commit }, check_id) {
        await this.$axios.$delete('checks/' + check_id);

        commit('REMOVE_CHECK', check_id);
    }
}
