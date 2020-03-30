<template>
  <div id="app">
    <div id="nav">
      <router-link :to="{ name: 'home' }">Home</router-link>
    </div>
    <router-view/>
  </div>
</template>
