<template>
  <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
      <nuxt-link to="/" class="navbar-brand">
        {{ $config.APP_NAME }}
      </nuxt-link>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Otevřít nabídku">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <ul v-if="$auth.user.role === 'admin'" class="navbar-nav me-auto">
          <!-- Left Side Of Navbar -->
<!--            <li class="nav-item">-->
<!--              <nuxt-link class="nav-link" to="/overview">Přehled</nuxt-link>-->
<!--            </li>-->
            <li class="nav-item">
              <nuxt-link class="nav-link" to="/warehouses">Sklady</nuxt-link>
            </li>
            <li class="nav-item">
              <nuxt-link class="nav-link" to="/warehouses/movements">Pohyby</nuxt-link>
            </li>
            <li class="nav-item">
              <nuxt-link class="nav-link" to="/discounts">Slevy</nuxt-link>
            </li>
            <li class="nav-item">
              <nuxt-link class="nav-link" to="/warehouses/checks">Kontroly</nuxt-link>
            </li>
            <li class="nav-item">
              <nuxt-link class="nav-link" to="/products">Produkty</nuxt-link>
            </li>
            <li class="nav-item">
              <nuxt-link class="nav-link" to="/warehouses/trash">Kompost/Odpad</nuxt-link>
            </li>
        </ul>

        <ul v-else class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" :href="`/warehouses/${$auth.user.warehouse_id}`">Můj sklad</a>
          </li>
          <li class="nav-item">
            <nuxt-link class="nav-link" to="/warehouses/movements">Pohyby</nuxt-link>
          </li>
          <li class="nav-item">
            <nuxt-link class="nav-link" to="/discounts">Slevy</nuxt-link>
          </li>
        </ul>

        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ms-auto">

          <li class="nav-item dropdown">
            <a id="navbarDropdown"
               class="nav-link dropdown-toggle"
               href="#" role="button"
               data-bs-toggle="dropdown"
               aria-haspopup="true"
               aria-expanded="false">
              {{ $auth.user.name }}
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
              <a class="dropdown-item"
                 @click.prevent="logout()">
                  Odhlásit se
              </a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: "NavBar",
  methods: {
    logout() {
        this.$auth.logout();
    }
  },
}
</script>

<style scoped>

</style>
