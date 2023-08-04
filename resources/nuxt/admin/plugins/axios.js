export default function ({ $axios, store }) {
  $axios.onRequest(request => {
    // console.log(request);
  });

  // API_URL rewrites baseUrl from nuxt.config.js, I need to suffix /v1 to it.
  $axios.setBaseURL(process.env.API_URL + "/api")

  $axios.onError(error => {
    let code = parseInt(error.response && error.response.status)

    if (code === 422) {
      store.dispatch('validation/clearErrors')
      store.dispatch('validation/setErrors', error.response.data.errors)

      return Promise.reject(error.response.data.errors);
    }

    if (code >= 400 && code !== 422) {
      console.log('Axios - response: ', error.response.data);

      let message = 'Undefined error';
      if (error.response.data && error.response.data.error && error.response.data.error.message) {
        message = error.response.data.error.message;
      } else {
        message = error.response.data.message
      }

      // store.dispatch('clearErrorMessage', message)
      store.dispatch('setErrorMessage', message)

      console.log('Axios - message: ', message);
      return Promise.reject(message);
    }

    console.log('Axios - error: ', error);
    return Promise.reject(error);
  })
}
