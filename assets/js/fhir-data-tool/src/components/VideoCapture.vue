<template>
  <div class="video-capture">
    <div>
      <video ref="video" width="640" height="480" autoplay muted/>
      <button @click="startVideo($refs.video)">start video</button>
      <button @click="stopVideo($refs.video)">stop video</button>
    </div>
  </div>
</template>

<script>


export default {
  name: 'VideoCapture',
  methods: {
    /**
     * start video stream
     */
    async startVideo(videoElem) {
      let stream = null;
      const constraints = {
        audio: true,
        video: {
          width: { min: 640, ideal: 1280, max: 1920 },
          height: { min: 480, ideal: 720, max: 1080 },
          facingMode: "user",
        }
      }

      try {
        stream = await navigator.mediaDevices.getUserMedia(constraints);
        /* use the stream */
        videoElem.srcObject = stream
      } catch(err) {
        /* handle the error */
        console.error(err)
      }
    },
    stopVideo(videoElem) {
      let stream = videoElem.srcObject
      if(!stream) return
      let tracks = stream.getTracks()

      tracks.forEach(function(track) {
        track.stop()
      })

      videoElem.srcObject = null
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
