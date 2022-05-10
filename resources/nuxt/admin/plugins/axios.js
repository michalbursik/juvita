export default function ({ $axios, store, $auth }) {
  // API_URL rewrites baseUrl from nuxt.config.js, I need to suffix /v1 to it.
  $axios.setBaseURL(process.env.API_URL + "/api")

  $axios.onError(error => {
    let code = parseInt(error.response && error.response.status)

    if (code === 422) {
      store.dispatch('validation/clearErrors')
      store.dispatch('validation/setErrors', error.response.data.error.errors)

      return Promise.reject(error.response.data.error.errors);
    }

  //   // Eprin - token after one hour expires. What nothing happens when pressing submit button. (API CALL)
  //   if (code === 401) {
  //     // TODO THIS DOES NOT WORK YET.
  //     // $auth.login('social')
  //   }
  //
    if (code >= 400 && code !== 422) {
      // error.message + ': ' +

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
  //
  // $axios.onResponse(response => {
  //   // TODO chyba validace v create strance rozbije presmerovani
  //   // if (response.data !== undefined && response.data.warnings !== undefined) {
  //   //   store.dispatch("setWarningMessages", response.data.warnings);
  //   // }
  //
  //   return Promise.resolve(response);
  // })
}
