<template>
  <div class="page__loading">
    <div class="loading">
      <div class="loading__header">
        <div class="loading__header-title">
          <div class="icon"></div>
          <p>Uploading {{ fileStore.file ? fileStore.file.name : 'file' }}</p>
        </div>
        <a href="javascript:void(0);" class="loading__header-close" @click="cancelUpload"></a>
      </div>
      <div class="loading__bottom">
        <div class="loading__bottom-bar">
          <div>
            <div :style="{ width: `${round(fileStore.progress)}%` }"></div>
          </div>
        </div>
        <div class="loading__bottom-status">
          <span>{{ (fileStore.uploadedSize / 1024 / 1024).toFixed(1) }}MB of {{ (fileStore.totalSize / 1024 / 1024).toFixed(1) }}MB</span>
          <span>{{ round(fileStore.progress) }}%</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {onMounted, ref} from 'vue';
import {useRouter} from 'vue-router';
import {useFileStore} from '@/stores/fileStore';

export default {
  setup() {
    const router = useRouter();
    const fileStore = useFileStore();
    const uploadInterval = ref(null);

    const round = (value) => Math.round(value);

    const startUpload = () => {
      fileStore.startUpload();
      uploadInterval.value = setInterval(() => {
        if (fileStore.progress < 100) {
          const sizeToUpload = Math.min(fileStore.totalSize - fileStore.uploadedSize, fileStore.totalSize / 100);
          fileStore.updateProgress(sizeToUpload);
        } else {
          clearInterval(uploadInterval.value);
          fileStore.finishUpload();
          router.push({
            path: '/uploaded',
            query: {fileName: fileStore.file.name}
          });
        }
      }, 100);
    };

    const cancelUpload = () => {
      clearInterval(uploadInterval.value);
      fileStore.cancelUpload();
      router.push('/');
    };

    onMounted(() => {
      if (fileStore.file) {
        startUpload();
      }
    });

    return {
      fileStore,
      cancelUpload,
      round
    };
  }
}
</script>
