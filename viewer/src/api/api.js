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
  getData (site, userId) {
    return api
      .post('getData.php', { site, userId })
      .then(res => {
        return res.data
      })
      .catch(err => {
        return Promise.reject(err)
      })
  },
  getSample (site, userId) {
    const selectedSample = { site: site, userId: userId }
    return api
      .post('getSpecificSample.php', selectedSample)
      .then(res => {
        const obj = {}
        obj.images = res.data
        this.getData(site, userId).then(res => {
          obj.data = res
        })
        console.log(obj)
        return obj
      })
      .catch(err => {
        return Promise.reject(err)
      })
  }
}
