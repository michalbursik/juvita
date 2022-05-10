import Vue from 'vue'

const global = {

}

// this is to help Webstorm with autocomplete
Vue.prototype.$global = global

export default ({ app }, inject) => {
  inject('global', global)
}

