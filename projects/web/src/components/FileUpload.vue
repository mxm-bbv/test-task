<template>
  <Popup type="warn" title="Warn" description="Maximum file size for download is 8 MB" />
  <div
      class="page__upload"
      @dragover="dragover"
      @dragleave="dragleave"
      @drop="drop"
  >
    <input
        type="file"
        name="file"
        id="fileInput"
        style="display: none"
        @change="onChange"
        ref="file"
        accept="*/*"
    />
    <div class="page__upload-icon"></div>
    <div class="page__upload-text">
      <label for="fileInput">
        <h3>Drag and drop a file, or <span>browse</span></h3>
        <p>Support all file types</p>
      </label>
    </div>
    <div class="page__card" v-if="files.length">
      <div class="page__card-group">
        <div>
          <template v-if="isImage(files[0])">
            <img :src="generateThumbnail(files[0])" alt="Preview Image" />
          </template>
          <template v-else>
            <div class="icon"></div>
          </template>
          <p>
            {{ makeName(files[0].name) }}
          </p>
        </div>
        <div>
          <button type="button" @click="remove">
            Remove file
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useFileStore } from '@/stores/fileStore';
import { useRouter } from 'vue-router';
import Popup from "@/components/Popups/Popup.vue";

export default {
  name: "FileUpload",
  components: { Popup },
  data() {
    return {
      isDragging: false,
      files: [],
    };
  },
  setup() {
    const fileStore = useFileStore();
    const router = useRouter();

    return { fileStore, router };
  },
  methods: {
    onChange() {
      this.handleFiles(this.$refs.file.files);
    },

    handleFiles(fileList) {
      const file = fileList[0];
      if (file.size > 8 * 1024 * 1024) return this.openWarningModal();

      this.files = [file];
      this.fileStore.setFile(file);

      this.router.push({ path: '/uploading', query: { file: file } });
    },

    isImage(file) {
      return file.type.startsWith("image/");
    },

    generateThumbnail(file) {
      return URL.createObjectURL(file);
    },

    makeName(name) {
      const parts = name.split(".");
      const baseName = parts[0];
      const extension = parts[parts.length - 1];

      if (baseName.length > 11) {
        return baseName.substring(0, 8) + "..." + extension;
      }

      return baseName + "." + extension;
    },

    remove() {
      this.files = [];
    },

    dragover(e) {
      e.preventDefault();
      this.isDragging = true;
    },

    dragleave() {
      this.isDragging = false;
    },

    drop(e) {
      e.preventDefault();
      const droppedFiles = [...e.dataTransfer.files];
      this.handleFiles(droppedFiles);
      this.isDragging = false;
    },

    openWarningModal() {
      const warningButton = document.querySelector('[data-popup="#warn"]');
      if (warningButton) {
        warningButton.click();
      }
    },
  },
};
</script>