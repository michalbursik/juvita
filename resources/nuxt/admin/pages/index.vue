<template>

</template>

<script>
export default {
  name: "index",
  mounted() {
    if (this.$auth.user.role === 'admin') {
      this.$router.push('/warehouses')
    } else {
      this.$router.push('/warehouses/' + this.$auth.user.warehouse_id)
    }
  }
}
</script>

<style scoped>

</style>
