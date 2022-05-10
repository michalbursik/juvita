<template>
  <div>
    <div class="container">
      <div class="row justify-content-center align-items-center vh-100">
        <div class="col-12 col-md-6 col-lg-4">
          <div class="form-group">
            <form @submit.prevent="login">
              <input v-model="credentials.email"
                     type="email"
                     class="form-control"
                     id="email"
                     name="email"
              >

              <input v-model="credentials.password"
                     type="password"
                     class="form-control mt-2"
                     id="password"
                     name="password"
              >

              <alert slim class="mt-2 mb-0" v-if="error" variant="danger">
                {{ error }}
              </alert>

              <button class="btn btn-primary mt-2" type="submit">Přihlásit se</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Alert from "~/components/Alert";
import {mapGetters} from "vuex";

export default {
  name: "login",
  layout: "plain",
  components: {Alert},
  computed: {
    ...mapGetters(["getErrorMessage", "getWarningMessages"])
  },
  data() {
    return {
      credentials: {
        email: '',
        password: '',
      },
      error: '',
    }
  },
  methods: {
    login() {
      this.$auth.loginWith('laravelSanctum', {
        data: this.credentials
      }).catch(r => {
        this.error = r.response.data.error.message;
      });
    }
  },
}
</script>

<style scoped>

</style>
