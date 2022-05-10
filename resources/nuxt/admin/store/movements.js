export const state = () => ({
  movements: [],
});

export const getters = {
  getAll: state => {
    return state.movements;
  },
  get: state => movement_id => {
    return state.movements.find(movement => movement.id === parseInt(movement_id));
  },
};

export const mutations = {
  SET_MOVEMENTS(state, movements) {
    state.movements = movements;
  },
  PUSH_OR_REPLACE_MOVEMENT(state, new_movement) {
    console.log('movements: PUSH_OR_REPLACE_MOVEMENT', state);

    let movement_index = state.movements.findIndex(movement => movement.id === parseInt(new_movement.id));
    console.log('movements: INDEX', movement_index);

    if (movement_index >= 0) {
      state.movements.splice(movement_index, 1, new_movement);
    } else {
      state.movements.push(new_movement);
    }
  },
  REMOVE_MOVEMENT(state, movement_id) {
    let movement_index = state.movements.findIndex(movement => movement.id === parseInt(movement_id));

    state.movements.splice(movement_index, 1);
  }
};

export const actions = {
  async fetchAll({commit}, params = null) {
    let movements = await this.$axios.$get('movements', {
      params: params
    });

    commit('SET_MOVEMENTS', movements.data);

    return movements;
  },
  async fetchAllAmounts({commit}, params = null) {
    let movements = await this.$axios.$get('movements/fetchAllAmounts', {
      params: params
    });

    // Different structure!
    // commit('SET_MOVEMENTS', movements.data);

    return movements;
  },
  async fetch({commit}, movement_id) {
    let movement = await this.$axios.$get('warehouses/movements/' + movement_id);

    commit('PUSH_OR_REPLACE_MOVEMENT', movement.data);

    return movement.data;
  },
  async store({commit}, movement) {
    movement = await this.$axios.$post('movements', movement);

    commit('PUSH_OR_REPLACE_MOVEMENT', movement.data);

    return movement.data;
  },
  async update({commit}, {movement, movement_id}) {
    movement = await this.$axios.$patch('warehouses/movements/' + movement_id, movement);

    commit('PUSH_OR_REPLACE_MOVEMENT', movement.data);

    return movement.data;
  },
  async delete({commit}, movement_id) {
    await this.$axios.$delete('warehouses/movements/' + movement_id);

    commit('REMOVE_MOVEMENT', movement_id);
  },
  async receipt({commit}, movement) {
    movement = await this.$axios.$post('warehouses/movements/receipt', movement);

    commit('PUSH_OR_REPLACE_MOVEMENT', movement.data);

    return movement.data;
  },
  async trash({commit}, movement) {
    movement = await this.$axios.$post('warehouses/movements/trash', movement);

    commit('PUSH_OR_REPLACE_MOVEMENT', movement.data);

    return movement.data;
  },
  async transmission({commit}, movement) {
    console.log('movements: START',);

    movement = await this.$axios.$post('warehouses/movements/transmission', movement);

    console.log('movements: ', movement);

    commit('PUSH_OR_REPLACE_MOVEMENT', movement.data);

    console.log('movements: after ', movement);

    return movement.data;
  },
}
