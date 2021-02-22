import Vue from 'vue'
import Vuex from 'vuex'
import Samples from '@/store/Samples'
import Vuetify from 'vuetify'

Vue.use(Vuetify)
Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    samples: Samples
  }
})
