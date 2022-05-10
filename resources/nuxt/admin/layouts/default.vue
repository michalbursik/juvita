<template>
  <div>
    <nav-bar></nav-bar>

    <alert class="mb-0" slim v-if="getErrorMessage" variant="danger">
      {{ getErrorMessage }}
    </alert>

    <alert v-for="(warningMessage, key) in getWarningMessages" :key="key" variant="warning">
      {{ warningMessage }}
    </alert>

    <main class="py-4">
      <Nuxt/>
    </main>
  </div>
</template>

<script>
import NavBar from "~/components/NavBar"
import Alert from "~/components/Alert"
import {mapGetters} from "vuex";

export default {
  components: {Alert, NavBar},
  computed: {
    ...mapGetters(["getErrorMessage", "getWarningMessages"])
  },
}
</script>

<style scoped>

</style>
