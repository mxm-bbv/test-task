<template>
  <div class="wrapper">
    <Header/>
    <main class="page">
      <div class="page__container">
        <RouterView/>
      </div>
    </main>
    <Footer/>
  </div>
</template>
<script setup>
import '@/assets/js/app.min.js?_v=20241007045833';
import '@/assets/css/style.min.css?_v=20241007045833'
import Header from "./components/Header.vue";
import Footer from "./components/Footer.vue";
import {useHead} from '@vueuse/head';

useHead({
  meta: [
    {charset: 'UTF-8'},
    {name: 'robots', content: 'noindex, nofollow'},
    {name: 'format-detection', content: 'telephone=no'},
    {
      name: 'description',
      content: 'CLOUD — a secure and convenient file storage service. Easily upload, share, and manage your data in the cloud.'
    },
    {name: 'keywords', content: 'cloud storage, file storage, data sharing, file security, CLOUD'},
    {property: 'og:type', content: 'website'},
    {property: 'og:title', content: 'CLOUD - Cloud File Storage'},
    {
      property: 'og:description',
      content: 'Store your important files in CLOUD. A convenient and secure cloud storage service with sharing capabilities.'
    },
    {property: 'og:url', content: import.meta.env.VITE_APP_URL},
    {property: 'og:image', content: ''},
    {name: 'twitter:card', content: 'summary_large_image'},
    {name: 'twitter:title', content: 'CLOUD - Cloud File Storage'},
    {
      name: 'twitter:description',
      content: 'Cloud storage for your files with a high level of security.'
    },
    {name: 'twitter:image', content: ''},
    {name: 'canonical', content: 'https://live.local/'}
  ],
  script: [
    {
      type: 'application/ld+json',
      innerHTML: {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "CLOUD - Secure File Storage",
        "url": import.meta.env.VITE_APP_URL,
        "description": "CLOUD is a secure and convenient file storage service that allows you to upload, share, and manage your files easily.",
        "publisher": {
          "@type": "Organization",
          "name": "CLOUD",
          "logo": {
            "@type": "ImageObject",
            "url": `${import.meta.env.VITE_APP_URL}/logo.png`
          }
        },
        "potentialAction": {
          "@type": "SearchAction",
          "target": `${import.meta.env.VITE_APP_URL}/?s={search_term_string}`,
          "query-input": "required name=search_term_string"
        }
      }
    },
    {
      type: 'application/ld+json',
      innerHTML: {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
          {
            "@type": "ListItem",
            "position": 1,
            "name": "My Files",
            "item": import.meta.env.VITE_APP_URL
          },
          {
            "@type": "ListItem",
            "position": 2,
            "name": "File Upload",
            "item": `${import.meta.env.VITE_APP_URL}/upload`
          }
        ]
      }
    }
  ]
});
</script>