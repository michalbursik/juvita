export default function ({ store }) {
  // Clear error message
  if (store.getters.shouldClearError) {
    store.dispatch("clearErrorMessage");
  } else {
    store.dispatch("markErrorForClearing");
  }

  // Clear warning messages
  if (store.getters.shouldClearWarnings) {
    store.dispatch("clearWarningMessages");
  } else {
    store.dispatch("markWarningForClearing");
  }

  // Clear validation error message
  if (store.getters["validation/shouldClear"]) {
    store.dispatch("validation/clearErrors");
  } else {
    store.dispatch("validation/markForClearing");
  }
}
