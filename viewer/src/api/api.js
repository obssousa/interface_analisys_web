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
      .get('../Samples/' + site + '/' + userId + '/trace.json')
      .then(res => { return res.data })
      .catch(err => {
        return Promise.reject(err)
      })
  },
  getSample (site, userId) {
    const selectedSample = { site: site, userId: userId }
    return api
      .post('getSpecificSample.php', selectedSample)
      .then(res => {
        const images = res.data
        console.log(images)
        this.getData(site, userId).then(res => {
          images.push(res)
        })
        return res.data
      })
      .catch(err => {
        return Promise.reject(err)
      })
  }
}
