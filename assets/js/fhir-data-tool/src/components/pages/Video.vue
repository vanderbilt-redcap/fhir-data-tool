<template>
  <div class="page">
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi natus illo quidem sit nulla magnam dolor alias quasi ipsam odit, neque nemo maxime expedita, tenetur illum suscipit quisquam, atque laborum!</p>
    <VideoCapture />
  </div>
</template>

<script>
import VideoCapture from '@/components/VideoCapture'

export default {
  name: 'VideoPage',
  components: {
    VideoCapture,
  },
  props: {
    msg: String
  },
  methods: {},
  computed: {}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>