<template>
  <div class="video-capture">
    <div id="video-container">
      <video ref="video" width="1280" height="720" autoplay muted/>
      <button @click="startDetection()">start video</button>
      <button @click="stopVideo()">stop video</button>
    </div>
  </div>
</template>

<script>

export default {
  name: 'VideoCapture',
  methods: {
    async startDetection() {
      await Promise.all([
        faceapi.nets.tinyFaceDetector.loadFromUri('/face-api/models'),
        faceapi.nets.faceLandmark68Net.loadFromUri('/face-api/models'),
        faceapi.nets.faceRecognitionNet.loadFromUri('/face-api/models'),
        faceapi.nets.faceExpressionNet.loadFromUri('/face-api/models'),
      ])
      this.startVideo()
    },
    /**
     * start video stream
     */
    async startVideo() {
      const video = this.$refs.video
      let stream = null;
      const constraints = {
        audio: true,
        video: {
          width: { min: 640, ideal: 1280, max: 1920 },
          height: { min: 480, ideal: 720, max: 1080 },
          facingMode: "user", // in mobile, prefer front camera to rear facing camera
        }
      }

      video.addEventListener('play', () => {
        const canvas = faceapi.createCanvasFromMedia(video)
        canvas.style.position= 'absolute'
        canvas.style.top= '0'
        canvas.style.left= '0'
        document.querySelector('#video-container').append(canvas)
        const displaySize = {
          width: video.width,
          height: video.height,
        }
        faceapi.matchDimensions(canvas, displaySize)
        setInterval(async () => {
          const detectionOptions = new faceapi.TinyFaceDetectorOptions()
          const detections = await faceapi.detectAllFaces(video, detectionOptions)
                              .withFaceLandmarks()
                              .withFaceExpressions()
          // console.log(detections)
          const resizedDetections = faceapi.resizeResults(detections, displaySize)
          canvas.getContext('2d').clearRect(0 ,0 , canvas.width, canvas.height)
          // draw square around face
          faceapi.draw.drawDetections(canvas, resizedDetections)
          // draw traits of the face (eyes, nose, mouth)
          faceapi.draw.drawFaceLandmarks(canvas, resizedDetections)
          // draw expresions (sad, neutral, happy, surprised)
          faceapi.draw.drawFaceExpressions(canvas, resizedDetections)
        }, 100)
      })

      try {
        stream = await navigator.mediaDevices.getUserMedia(constraints);
        /* use the stream */
        video.srcObject = stream
      } catch(err) {
        /* handle the error */
        console.error(err)
      }
    },
    stopVideo() {
      const video = this.$refs.video
      let stream = video.srcObject
      if(!stream) return
      let tracks = stream.getTracks()

      tracks.forEach(function(track) {
        track.stop()
      })

      video.srcObject = null
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#video-container {
  position: relative;
}
</style>
