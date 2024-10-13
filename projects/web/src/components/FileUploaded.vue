<template>
  <Popup
      type="delete"
      title="Delete"
      description="You really want to delete it?"
      :buttons="[0, 1]"
  />
  <Popup
      type="confirm"
      :title="`Confirm`"
      :description="`Are you sure you want to save ${currentName}?`"
      :buttons="[0, 2]"
      :name="currentName"
  />
  <div class="page__loading">
    <div class="uploaded">
      <div class="uploaded__file">
        <form action="" method="post" class="uploaded__file-title">
          <div class="icon"></div>
          <input
              autocomplete="off"
              type="text"
              name="fileName"
              v-model="currentName"
              :readonly="isReadonly"
              ref="myInput"
              @input="updateFileName"
          />
        </form>
        <a href="javascript:void(0)" @click.prevent="inputFocus" class="uploaded__file-rename"></a>
      </div>
      <p v-if="isDisabled">This file already exists</p>
      <div class="uploaded__buttons">
        <button type="button" class="uploaded__buttons-cancel" v-if="!isDisabled" @click="goHome">Cancel</button>
        <button type="button" class="uploaded__buttons-delete" v-if="isDisabled" data-popup="#delete">Delete</button>
        <button type="button" class="uploaded__buttons-save" data-popup="#confirm">Save</button>
      </div>
    </div>
  </div>
</template>

<script>
import {ref, watch, onMounted} from 'vue';
import Popup from "@/components/Popups/Popup.vue";
import {useFileStore} from '@/stores/fileStore';
import {useRouter} from "vue-router";

export default {
  name: "FileUploaded",
  components: {Popup},
  setup() {
    const fileStore = useFileStore();
    let currentName = ref(fileStore.file?.name.replace(/\.[^/.]+$/, '') || '');
    const isDisabled = ref(false);
    const isReadonly = ref(true);
    const myInput = ref(null);

    const checkFileExists = async (file) => {
      const formData = new FormData();
      formData.append('file', file);

      const response = await fetch('https://api.live.local/api/v1/file/check', {
        method: 'POST',
        headers: {
          'Accept': 'application/json'
        },
        body: formData
      });

      const data = await response.json();
      isDisabled.value = data.data.exists;
      console.log(isDisabled.value);
    };

    onMounted(async () => {
      await checkFileExists(fileStore.file);
    });

    watch(() => fileStore.file, (newFile) => {
      if (newFile && !currentName.value) {
        currentName.value = newFile.name.replace(/\.[^/.]+$/, '');
      }
    }, {immediate: true});

    const updateFileName = () => {
      currentName.value = myInput.value;
    };

    return {
      currentName,
      updateFileName,
      isDisabled,
      isReadonly,
      goHome() {
        const router = useRouter();
        router.push('/')
      },
      inputFocus() {
        isReadonly.value = false;
        myInput.value.focus();
      },
    };
  }
};
</script>
