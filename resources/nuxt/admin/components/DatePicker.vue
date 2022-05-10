<template>
  <datepicker :value="value"
              @input="onInput"
              :name="name"
              :bootstrap-styling="true"
              :input-class="inputClass"
              :language="datepickerLanguage.cs"
              :id="id"
              :clear-button="clearButton"
              :placeholder="placeholder"
              :clear-button-icon="icon"
              :minimum-view="minimumView"
              :format="format"
              :use-utc="useUtc"
  >
    <!-- Pass down slot -->
    <template slot="afterDateInput">
      <slot name="afterDateInput"></slot>
    </template>
  </datepicker>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
import {cs} from 'vuejs-datepicker/dist/locale'

export default {
  name: "DatePicker",
  components: {Datepicker},
  props: {
    value: {
      type: String|Date,
    },
    id: {
      type: String,
    },
    name: {
      type: String,
    },
    inputClass: {
      type: Object,
    },
    fillCurrentDay: {
      type: Boolean,
      default: true
    },
    placeholder: {
      type: String,
      default: null
    },
    clearButton: {
      type: Boolean,
      default: false
    },
    icon: {
      type: String,
      default: 'icon icon-clear-button'
    },
    minimumView: {
      type: String,
      default: null
    },
    format: {
      type: String,
      default: 'dd. MMM. yyyy'
    },
    useUtc: {
      type: Boolean,
      default: false
    }
  },
  mounted() {
    if (this.fillCurrentDay) {
      this.internalValue = new Date();
    }
  },
  data() {
    return {
      internalValue: "",
      datepickerLanguage: {
        cs: cs
      },
    }
  },
  methods: {
    onInput(value) {
      this.$emit('input', value.toISOString())
    }
  },
  watch: {}
}
</script>

<style scoped>

</style>
