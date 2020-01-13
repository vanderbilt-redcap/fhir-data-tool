
<template>
    <ul class="nav nav-tabs">
      <li v-for="(link, index) in links" :key="index" class="nav-item">
        <router-link class="nav-link" exact-active-class="active" :to="link.to">{{link.label}}</router-link>
      </li>
    </ul>
</template>

<script>
import {menu_links} from '@/variables'

export default {
  name: 'MenuLayout',
  data: () => ({
    links: menu_links
  }),
}
</script>

<style scoped>

@media only screen and (max-width: 768px) {

}
</style>