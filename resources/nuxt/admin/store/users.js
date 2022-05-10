export const state = () => ({
    users: [],
});

export const getters = {
    getAll: state => {
        return state.users;
    },
    get: state => user_id => {
        return state.users.find(user => user.id === parseInt(user_id));
    },
};

export const mutations = {
    SET_USERS(state, users) {
        state.users = users;
    },
    PUSH_OR_REPLACE_USER(state, new_user) {
        let user_index = state.users.findIndex(user => user.id === parseInt(new_user.id));

        if (user_index >= 0) {
            state.users.splice(user_index, 1, new_user);
        } else {
            state.users.push(new_user);
        }
    },
    REMOVE_USER(state, user_id) {
        let user_index = state.users.findIndex(user => user.id === parseInt(user_id));

        state.users.splice(user_index, 1);
    }
};

export const actions = {
    async fetchAll({ commit }, params = null) {
        let users = await this.$axios.$get('users', {
            params: params
        });

        commit('SET_USERS', users.data);

        return users;
    },
    async fetch({ commit }, user_id) {
        let user = await this.$axios.$get('users/' + user_id);

        commit('PUSH_OR_REPLACE_USER', user.data);

        return user.data;
    },
    async store({ commit }, user) {
        user = await this.$axios.$post('users', user);

        commit('PUSH_OR_REPLACE_USER', user.data);

        return user.data;
    },
    async update({ commit }, { user, user_id }) {
        user = await this.$axios.$patch('users/' + user_id, user);

        commit('PUSH_OR_REPLACE_USER', user.data);

        return user.data;
    },
    async delete({ commit }, user_id) {
        await this.$axios.$delete('users/' + user_id);

        commit('REMOVE_USER', user_id);
    },
}
