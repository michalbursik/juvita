export const state = () => ({
  errorMessage: null,
  warningMessages: [],
  shouldClearError: false,
  shouldClearWarnings: false,
});

export const getters = {
  getErrorMessage: (state) => {
    return state.errorMessage;
  },
  getWarningMessages: (state) => {
    return state.warningMessages;
  },
  shouldClearError: (state) => {
    return state.shouldClearError;
  },
  shouldClearWarnings: (state) => {
    return state.shouldClearWarnings;
  },
};

export const mutations = {
  SET_ERROR_MESSAGE(state, params) {
    state.errorMessage = params.message;
    state.shouldClearError = params.clear;
  },
  SET_WARNING_MESSAGES(state, warnings) {
    state.warningMessages = warnings;
    state.shouldClearWarnings = false;
  },
  MARK_ERROR_FOR_CLEARING(state) {
    state.shouldClearError = true;
  },
  MARK_WARNING_FOR_CLEARING(state) {
    state.shouldClearWarnings = true;
  },
};

export const actions = {
  setErrorMessage({ commit }, message, clear = true) {
    commit("SET_ERROR_MESSAGE", { message, clear });
  },
  setWarningMessages({ commit }, messages) {
    commit("SET_WARNING_MESSAGES", messages);
  },
  clearErrorMessage({ commit }) {
    commit("SET_ERROR_MESSAGE", { message: null, clear: true });
  },
  clearWarningMessages({ commit }) {
    commit("SET_WARNING_MESSAGES", null);
  },
  markErrorForClearing({ commit }) {
    commit("MARK_ERROR_FOR_CLEARING");
  },
  markWarningForClearing({ commit }) {
    commit("MARK_WARNING_FOR_CLEARING");
  },
};
