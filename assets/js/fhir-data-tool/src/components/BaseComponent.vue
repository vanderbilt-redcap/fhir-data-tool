<template>
  <div id="base">
    <section v-for="(value, key) in data" :key="key">
      <base-component v-if="typeof value === 'object'" :data="value" />
      <template v-else>
        <span>{{key}}:</span><span>{{value}}</span>
      </template>

    </section>
  </div>
</template>

<script>


export default {
  name: 'base-component',
  props: {
    data: {
      type: Object,
    }
  },
  methods: {
    onClick() {
      console.log(123)
    }
  }
}
</script>

<style>
</style>
