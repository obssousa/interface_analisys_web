<template>
  <div>
    <br />
    <v-row justify="center">
      <v-col class="d-flex" cols="12" sm="6">
        <v-select
          v-model="sample_selected"
          :items="samples"
          @change="changeSelectedSample()"
          filled
          label="Filled style"
          dense
          item-value="sample_name"
          item-text="sample_name"
        ></v-select>
      </v-col>
    </v-row>
    <v-row justify="center">
    </v-row>
    <div style="margin-top: 30px">
    <div id="container" style="width: 1366px; height: 2596px; position: absolute;">
    <canvas id='plotter' style="position: absolute; opacity: 75%;"> </canvas>
    <img class='img' />
    </div>
    </div>
  </div>
</template>

<script>
import api from '../api/api'
import simpleheat from 'simpleheat'
import mergeImages from 'merge-images'

export default {
  name: 'Main',
  props: {
    msg: String
  },
  data: () => ({
    sampleData: [],
    samples: [],
    images: [],
    selectedSample: [],
    ctx: 0,
    globalIndex: 1000000000000000,
    samples_url: [],
    sample_selected: '',
    selected_path: [],
    image_in_view: {},
    image_pos: 0,
    selectedObj: [],
    images_scroll: [],
    selected_img: [],
    heat: ''
  }),
  components: {},
  mounted () {
    api.getAllSamples().then(res => {
      this.samples = res
    })
  },
  methods: {
    changeSelectedSample () {
      api.getSample(this.sample_selected, 'c3954b1339e9ba5a7c546da866aafa3063256c812882c3da7fc6e9f3ad48e').then(res => {
        this.sampleData = res[res.length - 1]
        console.log('sampleData', this.sampleData)
        this.images = res.splice(res[res.length - 1])
        console.log('images', this.sampleData)
      })
      // this.interval = setInterval(() => this.findImagesInRealData(), 5000)
    },
    findImagesInRealData () {
      api.getImages(this.selected_path).then(response => {
        this.image_in_view = this.images[2]
        api.getSample(this.selectedObj.real_path).then(response => {
          this.findImageData()
        })
      })
    },
    findImages () {
      api.getImages(this.selected_path).then(response => {
        this.image_in_view = this.images[2]
      })
    },
    findTraceData () {
      api.getSample(this.selectedObj.real_path).then(response => {
        this.findImageData()
      })
    },
    groupBy2 (arr, property) {
      return arr.reduce(function (memo, x) {
        if (!memo[x[property]]) { memo[x[property]] = [] }
        memo[x[property]].push(x)
        return memo
      }, {})
    },
    findImageData () {
      var data = []
      var lastScroll = 0
      var imagesByTime = []
      var array = []
      this.selectedSample.sort((a, b) => (a.time > b.time) ? 1 : ((b.time > a.time) ? -1 : 0))
      var traceInAnalysis = this.selectedSample[this.selectedSample.length - 1]
      traceInAnalysis.forEach(time => {
        var image = this.findImageURL(time.image)
        var xdata = time.X
        var ydata = time.Y
        var zdata = 1
        data.push([xdata, ydata, zdata])
        if (image !== '') {
          if (array.length === 0) {
            array.push({ src: image, x: 0, y: lastScroll })
          }
          if ((time.scroll - lastScroll) >= 600) {
            lastScroll = time.scroll
            array.push({ src: image, x: 0, y: lastScroll })
          }
          // array.push({ src: image, x: 0, y: lastScroll })
        }
        // console.log(lastScroll)
        imagesByTime.push(array)
        lastScroll = 0
      })
      this.images_scroll = imagesByTime
      this.selected_img = imagesByTime[0]
      mergeImages(imagesByTime[0], { crossOrigin: 'Anonymous', height: 2613 })
        .then(b64 => { document.querySelector('img').src = b64 })
      this.drawSimpleheat(data)
    },
    drawSimpleheat (data) {
      var canvas = document.getElementById('plotter')
      var heat = simpleheat(canvas).data(data)
      var ctx = canvas.getContext('2d')
      canvas.height = 2613
      canvas.width = 9000
      ctx.clearRect(0, 0, 9000, 2613)
      // ctx.drawImage(background, 0, 0)
      // var img = document.getElementsByTagName('img')[0]
      // img.src = ctx.toDataURL()
      heat.clear()
      heat.max(1)
      heat.data(data)
      heat.draw(0.05)
      heat.radius(10, 30)
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#lateral .v-btn--example {
  bottom: 0;
  position: absolute;
  margin: 0 0 16px 16px;
}

#lateral .v-btn--example2 {
  bottom: 0;
  position: absolute;
  margin: 16px 0 0 0;
}

.ibagem {
  width: 100%;
  position: absolute;
  left:0px;
}

</style>
