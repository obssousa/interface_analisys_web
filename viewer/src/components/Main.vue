<template>
  <div>
    <br/>
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
      <v-col class="d-flex" cols="12" sm="6">
        <v-select
            v-model="user_selected"
            :items="users"
            @change="changeSelectedUser()"
            filled
            label="Filled style"
            dense
            item-value="sample_name"
            item-text="sample_name"
        ></v-select>
      </v-col>
    </v-row>
    <v-row justify="center">
      <v-card>
        <v-tabs
            v-model="tab"
            background-color="deep-purple accent-4"
            dark
        >
          <v-tab href="#heatmap">Heatmap</v-tab>
          <v-tab href="#vectorization">Vector</v-tab>
        </v-tabs>
      </v-card>
    </v-row>
    <v-tabs-items v-model="tab">
      <v-tab-item
          value="heatmap"
      >
        <div style="margin-top: 30px">
          <v-row align="center" justify="center">
          <div id="container" style="width: 50%; height: auto;">

            <canvas id='plotter' style="position: absolute; opacity: 75%;"></canvas>
            <img id='img' style="width: 100%; height: auto;" class='img'/>
          </div>
          </v-row>
        </div>
      </v-tab-item>
      <v-tab-item
          value="vectorization"
      >
        <v-card flat>
          <v-card-text>VAMOPORA</v-card-text>
        </v-card>
      </v-tab-item>
    </v-tabs-items>
  </div>
</template>

<script>
import api from '../api/api'
import simpleheat from 'simpleheat'
// import mergeImages from 'merge-images'

export default {
  name: 'Main',
  props: {
    msg: String
  },
  data: () => ({
    sampleData: [],
    samples: [],
    sampleImages: [],
    ctx: 0,
    globalIndex: 1000000000000000,
    samples_url: [],
    sample_selected: '',
    selected_path: [],
    image_in_view: {},
    image_pos: 0,
    selectedObj: [],
    users: [],
    user_selected: '',
    heat: '',
    tab: '',
    lastTime: 0
  }),
  components: {},
  mounted () {
    api.getAllSamples().then(res => {
      this.samples = res
    })
  },
  methods: {
    changeSelectedSample () {
      api.getAllUsers(this.sample_selected).then(res => {
        this.users = res
      })
    },
    changeSelectedUser () {
      this.getDataFromStorage()
      setInterval(() => this.getDataFromStorage(), 1000)
      setInterval(() => this.findImageData(), 1500)
    },
    getDataFromStorage () {
      api.getData(this.sample_selected, this.user_selected, this.lastTime).then(res => {
        this.sampleData = res
      })
    },
    findImageData () {
      var data = []; var image = null; var xdata = 0; var ydata = 0; var zdata = 0
      this.sampleData.sort((a, b) => (a.time > b.time) ? 1 : ((b.time > a.time) ? -1 : 0))
      var traceInAnalysis = this.sampleData[this.sampleData.length - 1]
      traceInAnalysis.forEach(analisys => {
        image = 'http://localhost:80/webtracer/Samples/' + this.sample_selected + '/' + this.user_selected + '/' + analisys.image
        xdata = analisys.X
        ydata = analisys.Y
        zdata = 1
        this.lastTime = analisys.time
        if (analisys.image !== '') { data.push([xdata, ydata, zdata]) }
      })
      document.querySelector('img').src = image
      this.drawSimpleheat(data)
    },
    drawSimpleheat (data) {
      var canvas = document.getElementById('plotter')
      this.fitToContainer(canvas)
      var img = document.getElementById('img')
      var heat = simpleheat(canvas).data(data)
      var ctx = canvas.getContext('2d')
      ctx.drawImage(img, 10, 10)
      heat.clear()
      heat.max(1)
      heat.data(data)
      heat.draw(0.05)
      heat.radius(10, 30)
    },
    fitToContainer (canvas) {
      // Make it visually fill the positioned parent
      canvas.style.width = '50%'
      canvas.style.height = '100%'
      // ...then set the internal size to match
      canvas.width = canvas.offsetWidth
      canvas.height = canvas.offsetHeight
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
  left: 0px;
}

</style>
