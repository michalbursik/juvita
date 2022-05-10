<template>
  <!--  TODO -->
  <div :class="'alert alert-' + variant" role="alert"
       :style="slim ? 'padding: .5rem 1rem': ''"
  >
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <slot></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Alert",
  props: {
    variant: {
      type: String,
      default: "success"
    },
    slim: {
      type: Boolean,
      default: false,
    },
    show: {
      type: Boolean,
      default: true,
    }
  }
}
</script>

<style scoped>

</style>
