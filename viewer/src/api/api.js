import axios from 'axios'

const api = axios.create({
  baseURL: `${process.env.VUE_APP_API_URL}`,
  timeout: 15000
})

export default {
  getAllSamples () {
    return api
      .get('getSamples.php')
      .then(res => res.data)
      .catch(err => {
        return Promise.reject(err)
      })
  },
  getAllUsers (site) {
    return api
      .post('getUsers.php', { site })
      .then(res => res.data)
      .catch(err => {
        return Promise.reject(err)
      })
  },
  getData (site, userId, time) {
    if (!time) {
      time = 0
    }
    return api
      .post('getData.php', { site, userId, time })
      .then(res => {
        return res.data
      })
      .catch(err => {
        return Promise.reject(err)
      })
  }
}
