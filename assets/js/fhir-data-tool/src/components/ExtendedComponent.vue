<template>
  <div>
    <h1>extended</h1>
    <BaseComponent :data="data"/>
  </div>
</template>

<script>
import BaseComponent from '@/components/BaseComponent'

export default {
  name: 'extended-component',
  extends: BaseComponent,
  components: {BaseComponent},
  methods: {
    onClick() {
      console.log(123)
    }
  }
}
</script>

<style>
</style>
