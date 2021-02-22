import axios from 'axios'

const Samples = {
  namespaced: true,
  state: {
    sample: [],
    images: [],
    specificSample: []
  },
  mutations: {
    mutSample (state, data) {
      state.sample = data
    },
    mutSampleSpecific (state, data) {
      state.specificSample = data
    },
    mutImages (state, data) {
      state.images = data
    }
  },
  actions: {
    fetchImages (context, paths) {
      return new Promise(function (resolve, reject) {
        axios
          .get('http://localhost/Dashboard/public/api/getImages.php', { params: { localUrl: paths.localPath, realUrl: paths.realPath } })
          .then((response) => {
            context.commit('mutImages', response.data)
            resolve()
          })
          .catch((error) => {
            alert(
              'Erro ao carregar imagens usuário.' +
              error
            )
            reject(error)
          })
      })
    },
    fetchSample (context) {
      return new Promise(function (resolve, reject) {
        axios
          .get('http://localhost/Dashboard/public/api/getSamples.php')
          .then((response) => {
            context.commit('mutSample', response.data)
            resolve(true)
          })
          .catch((error) => {
            alert(
              'Erro ao carregar dados de usuário.' +
              error
            )
            reject(error)
          })
      })
    },
    fetchSpecificSample (context, sampleURL) {
      return new Promise(function (resolve, reject) {
        axios
          .get('http://localhost/Dashboard/public/api/getSpecificSample.php', { params: { url: sampleURL } })
          .then((response) => {
            context.commit('mutSampleSpecific', response.data)
            resolve()
          })
          .catch((error) => {
            alert(
              'Erro ao carregar dados de usuário.' +
              error
            )
            reject(error)
          })
      })
    }
  },
  getters: {
    getSample: (state) => {
      return state.sample
    },
    getSpecSample: (state) => {
      return state.specificSample
    },
    getImages: (state) => {
      return state.images
    }
  }
}

export default Samples
