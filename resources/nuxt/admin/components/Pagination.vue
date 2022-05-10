<template>
  <nav v-if="parseInt(this.pagination.totalPages) !== 1" aria-label="Navigation">
    <ul class="pagination">
      <li @click.prevent="toPreviousPage()" class="page-item">
        <nuxt-link class="page-link" to="#" aria-label="Předchozí">
          <span aria-hidden="true">&laquo;</span>
        </nuxt-link>
      </li>

      <li v-for="page of pagination.totalPages"
          :key="page"
          @click.prevent="toPage(page)"
          class="page-item">
        <nuxt-link class="page-link" to="#">{{ page }}</nuxt-link>
      </li>

      <li class="page-item" @click.prevent="toNextPage()">
        <nuxt-link class="page-link" to="#" aria-label="Další">
          <span aria-hidden="true">&raquo;</span>
        </nuxt-link>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  name: "Pagination",
  props: {
    pagination: { // Laravel paginate() method.
      type: Object,
      default: () => {},
      required: true,
    },
  },
  methods: {
    toPreviousPage() {
      if (this.pagination.currentPage > 1) {
        this.pagination.currentPage -= 1;

        this.emitPagination();
      }
    },
    toPage(page) {
      this.pagination.currentPage = page;

      this.emitPagination();
    },
    toNextPage() {
      if (this.pagination.currentPage < this.pagination.totalPages) {
        this.pagination.currentPage += 1;

        this.emitPagination();
      }
    },
    emitPagination() {
      let {links, total, perPage, count, totalPages, ...pagination} = this.pagination;

      this.$emit('change', pagination);
    }
  },
}
</script>

<style scoped>

</style>
