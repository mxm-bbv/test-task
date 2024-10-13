<template>
  <div :id="type" aria-hidden="true" class="popup">
    <div class="popup__wrapper">
      <div class="popup__content">
        <div class="popup__content-title">
          <div class="icon">
            <div :class='`icon-` + type'></div>
          </div>
          <h1>{{ title }}</h1>
        </div>
        <div class="popup__content-description">
          <p>{{ description }}</p>
        </div>
        <div class="popup__content-buttons">
          <button v-if="isVariantVisible(0)" data-close type="button" class="cancel">Cancel</button>
          <button v-if="isVariantVisible(1)" data-close type="button" class="delete" @click="handleDelete()">Delete
          </button>
          <button v-if="isVariantVisible(2)" data-close type="button" class="confirm" @click="handleConfirm()">Confirm
          </button>
        </div>
      </div>
    </div>
  </div>
  <button type="button" style="visibility: hidden;" :data-popup="`#` + type"></button>
</template>

<script>
import {useRouter} from 'vue-router';
import {FilesApi} from "@/services/api/api.js";
import {useFileStore} from "@/stores/fileStore.js";
import {computed} from 'vue';

export default {
  props: {
    type: {
      type: String,
      default: 'warn',
      validator(value) {
        return ['warn', 'delete', 'confirm'].includes(value);
      },
    },
    title: {
      type: String,
      default: 'Warn',
    },
    description: {
      type: String,
      default: 'Warn text',
    },
    buttons: {
      type: [Array, Number],
    },
    url: {
      type: String,
    },
    name: {
      type: String,
    }
  },
  setup(props) {
    const router = useRouter();
    const fileStore = useFileStore();

    const variantArray = computed(() => {
      return Array.isArray(props.buttons) ? props.buttons : [props.buttons];
    });

    const isVariantVisible = (variantIndex) => {
      return variantArray.value.includes(variantIndex);
    };

    const handleConfirm = async () => {
      if (!await FilesApi.check(fileStore.file)) {
        await FilesApi.store(fileStore.file, props.name);
        router.push('/');
      }
    };

    const handleDelete = async () => {
      if (await FilesApi.check(fileStore.file)) {
        await FilesApi.destroy(fileStore.file);
        router.push('/');
      }
    };

    return {
      isVariantVisible,
      handleConfirm,
      handleDelete,
    };
  }
}
</script>