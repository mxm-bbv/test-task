<template>
  <header class="header">
    <div class="header__container">
      <div class="header__logo">
        <a href="javascript:void(0);" @click="goHome()" class="header__logo-svg">
          <img src="@/assets/img/logo.svg" alt="">
        </a>
        <div class="header__logo-line"><hr></div>
        <div class="header__logo-about">
          <p>File storage</p>
          <a href="https://t.me/mxm_bbv">Created by mxm-bbv</a>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  name: 'Header',
  methods: {
    goHome() {
      this.$router.push('/');
    }
  }
};
</script>