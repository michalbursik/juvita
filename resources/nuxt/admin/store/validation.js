export const state = () => ({
  errors: [],
  shouldClear: false,
})

export const getters = {
  getAll: state => {
    return state.errors
  },
  getCount: state => {
    return state.errors.length
  },
  getFirst: (state, getters) => input => {
    if (getters.exists(input)) {
      return state.errors[input][0]
    }

    return null
  },
  exists: state => input => {
    return typeof state.errors[input] !== 'undefined'
  },
  shouldClear: state => {
    return state.shouldClear
  },
}

export const mutations = {
  SET_ERRORS(state, errors) {
    state.errors = errors
  },
  REMOVE_ERROR(state, input) {
    if (typeof state.errors[input] !== 'undefined') {
      delete state.errors[input]
    }
  },
  MARK_FOR_CLEARING(state) {
    state.shouldClear = true
  },
}

export const actions = {
  setErrors({ commit }, errors) {
    commit('SET_ERRORS', errors)
  },
  clearErrors({ commit, getters }) {
    let errors = getters['getAll']

    for (let error in errors) {
      commit('REMOVE_ERROR', error)
    }
  },
  markForClearing({ commit }) {
    commit('MARK_FOR_CLEARING')
  },
}
