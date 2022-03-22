import Vue from 'vue'

export default ({$config: { ASSET_URL, APP_ENV }}) => {
  function asset(path) {
    if (!path) return '';
    if (APP_ENV === 'local') {
      return path;
    } else {
      return ASSET_URL + '/' + path.replace(/^\/|\/$/g, '');
    }
  }

  Vue.mixin({
    methods: {
      asset: asset
    },
  });

}
