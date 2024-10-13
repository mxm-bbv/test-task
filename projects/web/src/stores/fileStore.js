import { defineStore } from 'pinia';

export const useFileStore = defineStore('file', {
    state: () => ({
        file: null,
        progress: 0,
        uploadedSize: 0,
        totalSize: 0,
    }),
    actions: {
        startUpload() {
            this.progress = 0;
            this.uploadedSize = 0;
            this.totalSize = this.file.size;
        },
        updateProgress(size) {
            this.uploadedSize += size;
            this.progress = Math.min((this.uploadedSize / this.totalSize) * 100, 100);
        },
        finishUpload() {
            this.progress = 100;
        },
        cancelUpload() {
            this.file = null;
            this.progress = 0;
            this.uploadedSize = 0;
            this.totalSize = 0;
        },
        setFile(file) {
            this.file = file;
        },
        clearFile() {
            this.file = null;
        },
    },
});
