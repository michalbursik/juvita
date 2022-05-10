<template>
  <div class="row page-header" :class="containerClass">

    <div v-if="backUrl && !noBackButton" @click.prevent="$router.push(backUrl)" class="col-auto">
      <a class="btn btn-outline-primary shadow-none btn-lg" href="#" role="button">
        <svg class="icon btn__icon mx-n1" viewBox="0 0 16 16" width="16px" height="16px" aria-hidden="true">
          <path
            d="M3.844,6.992H16v2.01H3.818c1.882,1.881,3.742,3.74,5.588,5.584c-0.5,0.502-0.961,0.963-1.41,1.414C5.334,13.338,2.66,10.664,0,8.003L8.004,0c0.442,0.447,0.9,0.911,1.369,1.387C7.534,3.226,5.676,5.083,3.818,6.941C3.827,6.958,3.835,6.975,3.844,6.992"></path>
        </svg>
      </a>
    </div>

    <!-- --------------------------- Title ------------------------- -->
    <div class="col-auto me-auto">
      <h1 class="mb-0" :class="titleClass">{{ title }}</h1>
      <slot name="badges"></slot>
    </div>
    <!-- --------------------------- Search ------------------------- -->
    <slot name="search"></slot>
    <!-- --------------------------- Buttons ------------------------- -->
    <div class="col-auto" :class="buttonClass">
      <slot name="buttons"></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: "TopPanel",
  props: {
    title: {
      type: String,
    },
    titleClass: {
      type: String,
      default: '',
    },
    containerClass: {
      type: String,
    },
    buttonClass: {
      type: String,
    },
    noBackButton: {
      type: Boolean,
      default: false
    },
    backUrl: {
      type: String,
      default: null
    }
  }
}
</script>

<style scoped>

</style>
