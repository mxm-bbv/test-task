<template>
  <div class="page__box">
    <div class="page__box-content">
      <div class="page__title">
        <h1>Upload Files</h1>
        <p>Fill out the form below to download files anonymously.<br>Provide as much information as possible to ensure a fast and error-free download.</p>
      </div>
      <FileUpload />
    </div>
  </div>
</template>
<script setup>
import FileUpload from "../components/FileUpload.vue";

import { useHead } from '@vueuse/head'

useHead({
  title: 'CLOUD — Upload Files',
  meta: [
    { charset: 'UTF-8' },
    { name: 'robots', content: 'noindex, nofollow' },
    { name: 'format-detection', content: 'telephone=no' },
  ]
})
</script>